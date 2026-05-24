<?php

namespace Tests\Feature;

use App\Livewire\GameManager;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GameManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_searching_games_shows_live_matches(): void
    {
        $user = User::factory()->create();

        Game::create(['name' => 'TS4', 'slug' => 'ts4']);
        Game::create(['name' => 'TS Arena', 'slug' => 'ts-arena']);
        Game::create(['name' => 'Valorant', 'slug' => 'valorant']);

        Livewire::actingAs($user)
            ->test(GameManager::class)
            ->set('search', 'TS')
            ->assertSee('TS4')
            ->assertSee('TS Arena')
            ->assertDontSee('Valorant');
    }

    public function test_missing_game_can_be_added_to_database_and_profile(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(GameManager::class)
            ->set('search', 'New Arena')
            ->assertSee('Add "New Arena" to my profile', false)
            ->call('addNewGame')
            ->assertSet('search', '')
            ->assertSet('selectedGame', null);

        $this->assertDatabaseHas('games', [
            'name' => 'New Arena',
            'slug' => 'new-arena',
        ]);

        $this->assertDatabaseHas('game_user', [
            'user_id' => $user->id,
            'game_id' => Game::where('name', 'New Arena')->value('id'),
        ]);
    }
}
