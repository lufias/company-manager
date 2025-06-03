<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a company can be created with factory.
     */
    public function test_company_can_be_created_with_factory(): void
    {
        $company = Company::factory()->create();

        $this->assertInstanceOf(Company::class, $company);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $company->name,
            'email' => $company->email,
        ]);
    }

    /**
     * Test company fillable attributes.
     */
    public function test_company_has_correct_fillable_attributes(): void
    {
        $company = new Company();
        $expectedFillable = [
            'name',
            'email',
            'logo',
            'website',
            'created_by',
        ];

        $this->assertEquals($expectedFillable, $company->getFillable());
    }

    /**
     * Test company can be created with all fillable attributes.
     */
    public function test_company_can_be_created_with_all_attributes(): void
    {
        $user = User::factory()->create();
        
        $companyData = [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'logo' => 'https://example.com/logo.png',
            'website' => 'https://testcompany.com',
            'created_by' => $user->id,
        ];

        $company = Company::create($companyData);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Test Company', $company->name);
        $this->assertEquals('test@company.com', $company->email);
        $this->assertEquals('https://example.com/logo.png', $company->logo);
        $this->assertEquals('https://testcompany.com', $company->website);
        $this->assertEquals($user->id, $company->created_by);
    }

    /**
     * Test company belongs to a user (creator relationship).
     */
    public function test_company_belongs_to_user_creator(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(BelongsTo::class, $company->creator());
        $this->assertInstanceOf(User::class, $company->creator);
        $this->assertEquals($user->id, $company->creator->id);
        $this->assertEquals($user->name, $company->creator->name);
    }

    /**
     * Test company creator relationship returns correct user.
     */
    public function test_company_creator_relationship_returns_correct_user(): void
    {
        $user1 = User::factory()->create(['name' => 'John Doe']);
        $user2 = User::factory()->create(['name' => 'Jane Smith']);
        
        $company = Company::factory()->create(['created_by' => $user1->id]);

        $this->assertEquals('John Doe', $company->creator->name);
        $this->assertNotEquals('Jane Smith', $company->creator->name);
    }

    /**
     * Test company can exist without optional attributes.
     */
    public function test_company_can_be_created_with_minimal_data(): void
    {
        $user = User::factory()->create();
        
        $company = Company::create([
            'name' => 'Minimal Company',
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('Minimal Company', $company->name);
        $this->assertEquals($user->id, $company->created_by);
        $this->assertNull($company->email);
        $this->assertNull($company->logo);
        $this->assertNull($company->website);
    }

    /**
     * Test company uses HasFactory trait.
     */
    public function test_company_uses_has_factory_trait(): void
    {
        $company = new Company();
        $traits = class_uses($company);

        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
    }

    /**
     * Test company factory creates valid data.
     */
    public function test_company_factory_creates_valid_data(): void
    {
        $company = Company::factory()->make();

        $this->assertNotEmpty($company->name);
        $this->assertNotEmpty($company->email);
        $this->assertNotEmpty($company->logo);
        $this->assertNotEmpty($company->website);
        $this->assertIsString($company->name);
        $this->assertIsString($company->email);
        $this->assertIsString($company->logo);
        $this->assertIsString($company->website);
        $this->assertTrue(filter_var($company->email, FILTER_VALIDATE_EMAIL) !== false);
        $this->assertTrue(filter_var($company->logo, FILTER_VALIDATE_URL) !== false);
        $this->assertTrue(filter_var($company->website, FILTER_VALIDATE_URL) !== false);
    }

    /**
     * Test company factory creates company with user relationship.
     */
    public function test_company_factory_creates_with_user_relationship(): void
    {
        $company = Company::factory()->create();

        $this->assertNotNull($company->created_by);
        $this->assertInstanceOf(User::class, $company->creator);
        $this->assertDatabaseHas('users', ['id' => $company->created_by]);
    }

    /**
     * Test multiple companies can be created by the same user.
     */
    public function test_multiple_companies_can_be_created_by_same_user(): void
    {
        $user = User::factory()->create();
        
        $company1 = Company::factory()->create(['created_by' => $user->id]);
        $company2 = Company::factory()->create(['created_by' => $user->id]);

        $this->assertEquals($user->id, $company1->created_by);
        $this->assertEquals($user->id, $company2->created_by);
        $this->assertEquals($user->id, $company1->creator->id);
        $this->assertEquals($user->id, $company2->creator->id);
    }
} 