<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:list';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'List all users';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $users = User::with('role')->get();

    if ($users->isEmpty()) {
      $this->info('No users found.');
      return 0;
    }

    $this->info('Users in database:');
    $this->table(
      ['ID', 'Name', 'Email', 'Role', 'Created'],
      $users->map(function ($user) {
        return [
          $user->id,
          $user->name,
          $user->email,
          $user->role ? $user->role->name : 'No role',
          $user->created_at->format('Y-m-d H:i:s')
        ];
      })->toArray()
    );

    return 0;
  }
}
