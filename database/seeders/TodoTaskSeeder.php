<?php

namespace Database\Seeders;

use App\Models\TodoTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoTaskSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $projects = [
        'CRM Portal',
        'E-Commerce Platform',
        'Inventory System',
        'HR Dashboard',
        'Blog CMS',
        'SaaS Billing',
        'Job Board',
        'Real Estate Listings',
        'Learning Management',
        'Help Desk Ticketing',
        'Hotel Booking',
        'Fleet Management',
    ];

    private array $taskTemplates = [
        // Planning & Analysis
        'Review requirements for :project',
        'Draft technical spec for :project',
        'Database schema design for :project',
        'API endpoint planning for :project',
        'Estimate development effort for :project',
        'Wireframe review with design team',
        'Break down :project user stories into tasks',
        'Research best approach for :project integration',
        'Create ERD diagram for :project',
        'Define acceptance criteria for :project features',

        // Development
        'Build authentication system for :project',
        'Implement role-based access control in :project',
        'Create REST API for :project',
        'Set up queue jobs for :project',
        'Write Eloquent relationships for :project',
        'Build CRUD for :project module',
        'Implement file uploads in :project',
        'Create custom Artisan command for :project',
        'Set up event listeners in :project',
        'Write middleware for :project',
        'Build Livewire components for :project',
        'Create Blade templates for :project',
        'Implement search functionality in :project',
        'Add pagination to :project listings',
        'Write API resources and transformers for :project',
        'Set up Laravel Sanctum for :project',
        'Build notification system for :project',
        'Implement caching strategy for :project',
        'Create form requests and validation for :project',
        'Set up polymorphic relationships in :project',

        // Testing
        'Write feature tests for :project',
        'Write unit tests for :project services',
        'API testing with Pest for :project',
        'Set up CI pipeline for :project tests',
        'Write integration tests for :project payment flow',
        'Performance testing for :project endpoints',

        // Meetings & Communication
        'Daily standup meeting',
        'Sprint planning for :project',
        'Sprint retrospective',
        'Code review session with team',
        'One-on-one with tech lead',
        'Client demo for :project',
        'Requirements gathering call with :project client',
        'Architecture review meeting',
        'Backlog grooming session',
        'Knowledge sharing session — Laravel best practices',

        // Deployment & DevOps
        'Deploy :project to staging',
        'Deploy :project to production',
        'Configure server for :project',
        'Set up cron jobs for :project scheduler',
        'SSL certificate renewal for :project',
        'Database backup setup for :project',
        'Monitor :project error logs',
        'Optimize :project database queries',
        'Run Laravel upgrade for :project',
        'Set up Horizon for :project queue monitoring',

        // Bug Fixes & Maintenance
        'Fix 500 error on :project checkout page',
        'Debug N+1 query issue in :project',
        'Fix broken file upload in :project',
        'Resolve session timeout issue in :project',
        'Fix CORS errors in :project API',
        'Patch security vulnerability in :project dependencies',
        'Fix validation errors in :project registration',
        'Resolve database deadlock on :project',

        // Documentation
        'Write API documentation for :project',
        'Update :project README with setup instructions',
        'Document deployment process for :project',
        'Create onboarding guide for :project',

        // Learning & Growth
        'Watch Laracon talk on :topic',
        'Read Laravel News digest',
        'Experiment with Laravel Reverb broadcasting',
        'Learn Pest testing framework',
        'Study Laravel queues deep dive',
    ];

    private array $subtaskTemplates = [
        'Write migration for :detail',
        'Create model and relationships for :detail',
        'Build controller methods for :detail',
        'Add form request validation for :detail',
        'Write service class for :detail',
        'Create Blade view for :detail',
        'Add API route for :detail',
        'Write Pest tests for :detail',
        'Update Postman collection for :detail',
        'Test edge cases for :detail',
        'Review code with team for :detail',
        'Update changelog for :detail',
        'Push branch and create PR for :detail',
        'Resolve PR feedback for :detail',
        'Merge and deploy :detail',
        'Seed sample data for :detail',
        'Add error handling for :detail',
        'Refactor :detail for readability',
        'Add logging for :detail',
        'Document :detail in Notion',
        'Benchmark performance of :detail',
        'Add rate limiting to :detail',
    ];

    public function run(): void
    {
        $userId = 1; // admin user

        for ($i = 0; $i < 100; $i++) {
            $project = fake()->randomElement($this->projects);
            $title  = str_replace(':project', $project, fake()->randomElement($this->taskTemplates));

            // Occasionally use a learning topic
            if (str_contains($title, ':topic')) {
                $title = str_replace(':topic', fake()->randomElement([
                    'Laravel Queues',
                    'Livewire 3',
                    'Pest Testing',
                    'Docker',
                    'Redis',
                    'MySQL optimization',
                    'Tailwind CSS',
                ]), $title);
            }

            $parent = TodoTask::create([
                'title'       => $title,
                'is_complete'  => fake()->boolean(35),
                'parent_id'   => null,
                'user_id'     => $userId,
                'ordered'     => $i,
            ]);

            // Create 2–5 subtasks for each parent
            $subtaskCount = rand(2, 5);
            for ($j = 0; $j < $subtaskCount; $j++) {
                $detail = fake()->randomElement([
                    'the ' . fake()->word() . ' module',
                    'the ' . fake()->word() . ' endpoint',
                    'user profile page',
                    'dashboard widgets',
                    'export feature',
                    'import feature',
                    'notification emails',
                    'PDF reports',
                    'the settings panel',
                    'bulk actions',
                    'search filters',
                    'activity logs',
                ]);

                TodoTask::create([
                    'title'       => str_replace(':detail', $detail, fake()->randomElement($this->subtaskTemplates)),
                    'is_complete' => fake()->boolean(25),
                    'parent_id'   => $parent->id,
                    'user_id'     => $userId,
                    'ordered'     => $j,
                ]);
            }
        }
    }
}
