<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kelas = ['X', 'XI', 'XII'];
        $jurusan = ['TKJ', 'RPL', 'MM', 'TAV', 'TKR', 'TBS'];
        $kelasNum = $this->faker->randomElement(['1', '2', '3']);
        $jurusan = $this->faker->randomElement($jurusan);

        return [
            'nis' => $this->faker->unique()->numerify('########'), // 8 digit NIS
            'nama' => $this->faker->name(),
            'kelas' => $kelas . ' ' . $jurusan . ' ' . $kelasNum,
        ];
    }

    /**
     * Indicate that the siswa has a user account.
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }
}

