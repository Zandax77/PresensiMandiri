<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'siswa',
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the user is a siswa.
     */
    public function siswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'siswa',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is a super admin.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is a wali kelas.
     */
    public function waliKelas(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'wali_kelas',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is BK.
     */
    public function bk(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'bk',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the user is kesiswaan (admin).
     */
    public function kesiswaan(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'kesiswaan',
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
