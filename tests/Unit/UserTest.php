<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can be created with factory.
     */
    public function test_user_can_be_created_with_factory(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Test user fillable attributes.
     */
    public function test_user_has_correct_fillable_attributes(): void
    {
        $user = new User();
        $expectedFillable = [
            'name',
            'email',
            'password',
            'is_admin',
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /**
     * Test user hidden attributes.
     */
    public function test_user_has_correct_hidden_attributes(): void
    {
        $user = new User();
        $expectedHidden = [
            'password',
            'remember_token',
        ];

        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    /**
     * Test user can be created with all fillable attributes.
     */
    public function test_user_can_be_created_with_all_attributes(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'is_admin' => true,
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue($user->is_admin);
        // Password should be hashed
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test user can be created with minimal data.
     */
    public function test_user_can_be_created_with_minimal_data(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Jane Doe', $user->name);
        $this->assertEquals('jane@example.com', $user->email);
        $this->assertFalse($user->is_admin); // Should default to false
    }

    /**
     * Test user casts attributes correctly.
     */
    public function test_user_casts_attributes_correctly(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1,
            'email_verified_at' => now(),
        ]);

        // Test boolean casting
        $this->assertIsBool($user->is_admin);
        $this->assertTrue($user->is_admin);

        // Test datetime casting
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);

        // Test password hashing
        $this->assertNotEmpty($user->password);
        $this->assertStringStartsWith('$2y$', $user->password); // bcrypt hash format
    }

    /**
     * Test isAdmin method returns correct boolean value.
     */
    public function test_is_admin_method_returns_correct_value(): void
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    /**
     * Test user has many companies relationship.
     */
    public function test_user_has_many_companies(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(HasMany::class, $user->companies());
    }

    /**
     * Test user companies relationship returns correct companies.
     */
    public function test_user_companies_relationship_returns_correct_companies(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $company1 = Company::factory()->create(['created_by' => $user1->id]);
        $company2 = Company::factory()->create(['created_by' => $user1->id]);
        $company3 = Company::factory()->create(['created_by' => $user2->id]);

        $user1Companies = $user1->companies;
        $user2Companies = $user2->companies;

        $this->assertCount(2, $user1Companies);
        $this->assertCount(1, $user2Companies);
        $this->assertTrue($user1Companies->contains($company1));
        $this->assertTrue($user1Companies->contains($company2));
        $this->assertFalse($user1Companies->contains($company3));
        $this->assertTrue($user2Companies->contains($company3));
    }

    /**
     * Test user uses required traits.
     */
    public function test_user_uses_required_traits(): void
    {
        $user = new User();
        $traits = class_uses($user);

        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
        $this->assertContains('Illuminate\Notifications\Notifiable', $traits);
    }

    /**
     * Test user factory creates valid data.
     */
    public function test_user_factory_creates_valid_data(): void
    {
        $user = User::factory()->make();

        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
        $this->assertIsString($user->name);
        $this->assertIsString($user->email);
        $this->assertIsString($user->password);
        $this->assertIsBool($user->is_admin);
        $this->assertTrue(filter_var($user->email, FILTER_VALIDATE_EMAIL) !== false);
    }

    /**
     * Test user password is automatically hashed.
     */
    public function test_user_password_is_automatically_hashed(): void
    {
        $plainPassword = 'plain-text-password';
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $plainPassword,
        ]);

        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test user hidden attributes are not included in array/JSON.
     */
    public function test_user_hidden_attributes_not_in_array(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
        $this->assertArrayHasKey('name', $userArray);
        $this->assertArrayHasKey('email', $userArray);
        $this->assertArrayHasKey('is_admin', $userArray);
    }

    /**
     * Test user can have multiple companies.
     */
    public function test_user_can_have_multiple_companies(): void
    {
        $user = User::factory()->create();
        
        $companies = Company::factory()->count(3)->create(['created_by' => $user->id]);

        $this->assertCount(3, $user->companies);
        foreach ($companies as $company) {
            $this->assertTrue($user->companies->contains($company));
            $this->assertEquals($user->id, $company->created_by);
        }
    }

    /**
     * Test user can exist without companies.
     */
    public function test_user_can_exist_without_companies(): void
    {
        $user = User::factory()->create();

        $this->assertCount(0, $user->companies);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->companies);
    }

    /**
     * Test admin user creation and verification.
     */
    public function test_admin_user_creation_and_verification(): void
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        $regularUser = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($adminUser->is_admin);
        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->is_admin);
        $this->assertFalse($regularUser->isAdmin());
    }

    /**
     * Test user model extends Authenticatable.
     */
    public function test_user_extends_authenticatable(): void
    {
        $user = new User();
        
        $this->assertInstanceOf(\Illuminate\Foundation\Auth\User::class, $user);
    }
} 