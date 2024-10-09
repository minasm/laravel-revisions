<?php

namespace Neurony\Revisions\Tests;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Neurony\Revisions\Tests\Models\Author;
use Neurony\Revisions\Tests\Models\Post;
use Neurony\Revisions\Tests\Models\Tag;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public Post $post;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database and migrate the necessary tables.
     */
    protected function setUpDatabase(Application $app): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    protected function makeModels(?Post $model = null): void
    {
        $model = $model instanceof Post ? $model : new Post;

        for ($i = 1; $i <= 3; $i++) {
            Tag::create([
                'name' => 'Tag name '.$i,
            ]);
        }

        $author = Author::create([
            'title' => 'Author title',
            'name' => 'Author name',
            'age' => 30,
        ]);

        $this->post = $model->create([
            'author_id' => $author->id,
            'name' => 'Post name',
            'slug' => 'post-slug',
            'content' => 'Post content',
            'votes' => 10,
            'views' => 100,
        ]);

        $this->post->reply()->create([
            'post_id' => $this->post->id,
            'subject' => 'Reply subject',
            'content' => 'Reply content',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $this->post->comments()->create([
                'id' => $i,
                'post_id' => $this->post->id,
                'title' => 'Comment title '.$i,
                'content' => 'Comment content '.$i,
                'date' => Carbon::now(),
                'active' => true,
            ]);
        }

        $this->post->tags()->attach(Tag::pluck('id')->toArray());

        $this->post = $this->post->fresh();
    }
}
