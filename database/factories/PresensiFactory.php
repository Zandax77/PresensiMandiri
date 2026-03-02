<?php

namespace Database\Factories;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Presensi>
 */
class PresensiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Presensi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['hadir', 'alfa', 'izin', 'sakit']);
        $tanggal = $this->faker->dateTimeBetween('-30 days', 'today');

        // Jam datang antara 06:00 - 07:30
        $jamDatang = $this->faker->time('H:i:s', sprintf('%02d:00:00', $this->faker->numberBetween(6, 7)));
        if ($this->faker->boolean(70)) {
            // 70% datang tepat waktu (sebelum jam 7)
            $jamDatang = sprintf('%02d:%02d:00', $this->faker->numberBetween(6, 6), $this->faker->numberBetween(0, 59));
        } else {
            // 30% terlambat
            $jamDatang = sprintf('%02d:%02d:00', $this->faker->numberBetween(7, 8), $this->faker->numberBetween(0, 59));
        }

        // Jam pulang antara 14:00 - 15:30
        $jamPulang = sprintf('%02d:%02d:00', $this->faker->numberBetween(14, 15), $this->faker->numberBetween(0, 59));

        return [
            'tanggal' => $tanggal->format('Y-m-d'),
            'jam_datang' => $status === 'alfa' ? null : $jamDatang,
            'jam_pulang' => $status === 'alfa' ? null : ($status === 'izin' ? null : $jamPulang),
            'status' => $status,
            'latitude' => $this->faker->latitude(-6.2, -6.1),
            'longitude' => $this->faker->longitude(106.8, 106.9),
            'keterangan' => $this->faker->optional(30)->sentence(),
        ];
    }

    /**
     * Indicate that the presensi is present (hadir).
     */
    public function hadir(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hadir',
            'jam_datang' => sprintf('%02d:%02d:00', $this->faker->numberBetween(6, 7), $this->faker->numberBetween(0, 59)),
            'jam_pulang' => sprintf('%02d:%02d:00', $this->faker->numberBetween(14, 15), $this->faker->numberBetween(0, 59)),
        ]);
    }

    /**
     * Indicate that the presensi is absent (alfa).
     */
    public function alfa(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'alfa',
            'jam_datang' => null,
            'jam_pulang' => null,
            'keterangan' => null,
        ]);
    }

    /**
     * Indicate that the presensi is izin.
     */
    public function izin(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'izin',
            'jam_datang' => null,
            'jam_pulang' => null,
            'keterangan' => 'Izin ',
        ]);
    }

    /**
     * Indicate that the presensi is sakit.
     */
    public function sakit(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sakit',
            'jam_datang' => null,
            'jam_pulang' => null,
            'keterangan' => 'Sakit ',
        ]);
    }
}

