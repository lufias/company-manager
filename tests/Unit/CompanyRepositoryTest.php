<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\Interfaces\FileSystemRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $companyRepository;
    protected $fileSystemRepositoryMock;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a mock for FileSystemRepositoryInterface
        $this->fileSystemRepositoryMock = Mockery::mock(FileSystemRepositoryInterface::class);
        
        // Create the repository with the mocked dependency
        $this->companyRepository = new CompanyRepository($this->fileSystemRepositoryMock);
        
        // Create a user for authentication
        $this->user = User::factory()->create();
        Auth::login($this->user);
        
        // Mock Storage facade
        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that the repository can retrieve all companies.
     */
    public function test_all_returns_all_companies(): void
    {
        // Create test companies
        $companies = Company::factory()->count(3)->create();

        $result = $this->companyRepository->all();

        $this->assertCount(3, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
        
        // Verify all companies are returned
        foreach ($companies as $company) {
            $this->assertTrue($result->contains('id', $company->id));
        }
    }

    /**
     * Test that the repository returns empty collection when no companies exist.
     */
    public function test_all_returns_empty_collection_when_no_companies(): void
    {
        $result = $this->companyRepository->all();

        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    /**
     * Test pagination with default parameters.
     */
    public function test_paginate_with_default_parameters(): void
    {
        // Create test companies with different updated_at times
        $company1 = Company::factory()->create(['updated_at' => now()->subDays(3)]);
        $company2 = Company::factory()->create(['updated_at' => now()->subDays(1)]);
        $company3 = Company::factory()->create(['updated_at' => now()->subDays(2)]);

        $result = $this->companyRepository->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(3, $result->total());
        
        // Verify ordering (desc by updated_at)
        $items = $result->items();
        $this->assertEquals($company2->id, $items[0]->id); // Most recent
        $this->assertEquals($company3->id, $items[1]->id); // Middle
        $this->assertEquals($company1->id, $items[2]->id); // Oldest
    }

    /**
     * Test pagination with custom parameters.
     */
    public function test_paginate_with_custom_parameters(): void
    {
        Company::factory()->count(20)->create();

        $result = $this->companyRepository->paginate(5, 'name', 'asc');

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->perPage());
        $this->assertEquals(20, $result->total());
        $this->assertEquals(4, $result->lastPage());
    }

    /**
     * Test creating a company without logo.
     */
    public function test_create_company_without_logo(): void
    {
        $data = [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'https://testcompany.com'
        ];

        $company = $this->companyRepository->create($data);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Test Company', $company->name);
        $this->assertEquals('test@company.com', $company->email);
        $this->assertEquals('https://testcompany.com', $company->website);
        $this->assertEquals($this->user->id, $company->created_by);
        $this->assertNull($company->logo);
        
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'https://testcompany.com',
            'created_by' => $this->user->id
        ]);
    }

    /**
     * Test creating a company with logo.
     */
    public function test_create_company_with_logo(): void
    {
        // Mock the file system repository
        $this->fileSystemRepositoryMock
            ->shouldReceive('createModuleStorageStructure')
            ->with('companies')
            ->once()
            ->andReturn(true);

        $logo = UploadedFile::fake()->create('logo.jpg', 100);
        
        $data = [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'https://testcompany.com',
            'logo' => $logo
        ];

        $company = $this->companyRepository->create($data);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Test Company', $company->name);
        $this->assertEquals('test@company.com', $company->email);
        $this->assertEquals('https://testcompany.com', $company->website);
        $this->assertEquals($this->user->id, $company->created_by);
        $this->assertNotNull($company->logo);
        $this->assertStringStartsWith('companies/', $company->logo);
        
        // Verify file was stored
        $this->assertTrue(Storage::disk('public')->exists($company->logo));
    }

    /**
     * Test creating a company throws exception when storage structure creation fails.
     */
    public function test_create_company_throws_exception_when_storage_fails(): void
    {
        // Mock the file system repository to return false
        $this->fileSystemRepositoryMock
            ->shouldReceive('createModuleStorageStructure')
            ->with('companies')
            ->once()
            ->andReturn(false);

        $logo = UploadedFile::fake()->create('logo.jpg', 100);
        
        $data = [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'https://testcompany.com',
            'logo' => $logo
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to create storage directory structure for companies');

        $this->companyRepository->create($data);
    }

    /**
     * Test updating a company without changing logo.
     */
    public function test_update_company_without_logo_change(): void
    {
        $company = Company::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@company.com'
        ]);

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@company.com',
            'website' => 'https://updated.com'
        ];

        $updatedCompany = $this->companyRepository->update($company, $data);

        $this->assertInstanceOf(Company::class, $updatedCompany);
        $this->assertEquals('Updated Name', $updatedCompany->name);
        $this->assertEquals('updated@company.com', $updatedCompany->email);
        $this->assertEquals('https://updated.com', $updatedCompany->website);
        
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Updated Name',
            'email' => 'updated@company.com',
            'website' => 'https://updated.com'
        ]);
    }

    /**
     * Test updating a company with new logo (replacing old file logo).
     */
    public function test_update_company_with_new_logo_replacing_old_file(): void
    {
        // Create existing logo file
        $oldLogo = UploadedFile::fake()->create('old-logo.jpg', 100);
        $oldLogoPath = $oldLogo->store('companies', 'public');
        
        $company = Company::factory()->create([
            'logo' => $oldLogoPath
        ]);

        // Mock the file system repository
        $this->fileSystemRepositoryMock
            ->shouldReceive('createModuleStorageStructure')
            ->with('companies')
            ->once()
            ->andReturn(true);

        $newLogo = UploadedFile::fake()->create('new-logo.jpg', 100);
        
        $data = [
            'name' => 'Updated Company',
            'email' => 'updated@company.com',
            'website' => 'https://updated.com',
            'logo' => $newLogo
        ];

        $updatedCompany = $this->companyRepository->update($company, $data);

        $this->assertInstanceOf(Company::class, $updatedCompany);
        $this->assertNotEquals($oldLogoPath, $updatedCompany->logo);
        $this->assertStringStartsWith('companies/', $updatedCompany->logo);
        
        // Verify old file was deleted and new file exists
        $this->assertFalse(Storage::disk('public')->exists($oldLogoPath));
        $this->assertTrue(Storage::disk('public')->exists($updatedCompany->logo));
    }

    /**
     * Test updating a company with new logo (old logo is URL).
     */
    public function test_update_company_with_new_logo_old_is_url(): void
    {
        $company = Company::factory()->create([
            'logo' => 'https://example.com/old-logo.jpg'
        ]);

        // Mock the file system repository
        $this->fileSystemRepositoryMock
            ->shouldReceive('createModuleStorageStructure')
            ->with('companies')
            ->once()
            ->andReturn(true);

        $newLogo = UploadedFile::fake()->create('new-logo.jpg', 100);
        
        $data = [
            'name' => 'Updated Company',
            'email' => 'updated@company.com',
            'website' => 'https://updated.com',
            'logo' => $newLogo
        ];

        $updatedCompany = $this->companyRepository->update($company, $data);

        $this->assertInstanceOf(Company::class, $updatedCompany);
        $this->assertNotEquals('https://example.com/old-logo.jpg', $updatedCompany->logo);
        $this->assertStringStartsWith('companies/', $updatedCompany->logo);
        
        // Verify new file exists (old URL logo shouldn't be deleted)
        $this->assertTrue(Storage::disk('public')->exists($updatedCompany->logo));
    }

    /**
     * Test updating a company throws exception when storage structure creation fails.
     */
    public function test_update_company_throws_exception_when_storage_fails(): void
    {
        $company = Company::factory()->create();

        // Mock the file system repository to return false
        $this->fileSystemRepositoryMock
            ->shouldReceive('createModuleStorageStructure')
            ->with('companies')
            ->once()
            ->andReturn(false);

        $logo = UploadedFile::fake()->create('logo.jpg', 100);
        
        $data = [
            'name' => 'Updated Company',
            'email' => 'updated@company.com',
            'website' => 'https://updated.com',
            'logo' => $logo
        ];

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to create storage directory structure for companies');

        $this->companyRepository->update($company, $data);
    }

    /**
     * Test deleting a company without logo.
     */
    public function test_delete_company_without_logo(): void
    {
        $company = Company::factory()->create(['logo' => null]);

        $result = $this->companyRepository->delete($company);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    /**
     * Test deleting a company with file logo.
     */
    public function test_delete_company_with_file_logo(): void
    {
        // Create logo file
        $logo = UploadedFile::fake()->create('logo.jpg', 100);
        $logoPath = $logo->store('companies', 'public');
        
        $company = Company::factory()->create(['logo' => $logoPath]);

        $result = $this->companyRepository->delete($company);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        
        // Verify logo file was deleted
        $this->assertFalse(Storage::disk('public')->exists($logoPath));
    }

    /**
     * Test deleting a company with URL logo.
     */
    public function test_delete_company_with_url_logo(): void
    {
        $company = Company::factory()->create([
            'logo' => 'https://example.com/logo.jpg'
        ]);

        $result = $this->companyRepository->delete($company);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
        
        // URL logo shouldn't be deleted (no file operations)
    }

    /**
     * Test repository constructor sets file system repository dependency.
     */
    public function test_constructor_sets_file_system_repository(): void
    {
        $fileSystemRepo = Mockery::mock(FileSystemRepositoryInterface::class);
        $repository = new CompanyRepository($fileSystemRepo);

        $reflection = new \ReflectionClass($repository);
        $property = $reflection->getProperty('fileSystemRepository');
        $property->setAccessible(true);

        $this->assertSame($fileSystemRepo, $property->getValue($repository));
    }

    /**
     * Test creating company with minimal required data.
     */
    public function test_create_company_with_minimal_data(): void
    {
        $data = [
            'name' => 'Minimal Company',
            'email' => null,
            'website' => null
        ];

        $company = $this->companyRepository->create($data);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Minimal Company', $company->name);
        $this->assertNull($company->email);
        $this->assertNull($company->website);
        $this->assertNull($company->logo);
        $this->assertEquals($this->user->id, $company->created_by);
    }

    /**
     * Test updating company with minimal data.
     */
    public function test_update_company_with_minimal_data(): void
    {
        $company = Company::factory()->create();

        $data = [
            'name' => 'Updated Minimal',
            'email' => null,
            'website' => null
        ];

        $updatedCompany = $this->companyRepository->update($company, $data);

        $this->assertEquals('Updated Minimal', $updatedCompany->name);
        $this->assertNull($updatedCompany->email);
        $this->assertNull($updatedCompany->website);
    }
} 