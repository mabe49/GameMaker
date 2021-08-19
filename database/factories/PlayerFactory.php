<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Player::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'win_count' => $this->faker->numberBetween(0, 10),
            'lose_count' => $this->faker->numberBetween(0, 10),
            'game_count' => 20,
            'player_strength' => $this->faker->numberBetween(0, 6),
            'rest_count' => $this->faker->numberBetween(0, 3),
            'attendance' => $this->faker->boolean(50),
        ];
    }
}
