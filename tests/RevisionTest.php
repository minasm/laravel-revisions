<?php

namespace Neurony\Revisions\Tests;

use Neurony\Revisions\Models\Revision;
use PHPUnit\Framework\Attributes\Test;

class RevisionTest extends TestCase
{
    #[Test]
    public function it_can_create_a_revision()
    {
        $revision = Revision::create([
            'user_id' => 1,
            'revisionable_id' => 1,
            'revisionable_type' => 'App\\Post',
            'metadata' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ],
        ]);

        $this->assertInstanceOf(Revision::class, $revision);
        $this->assertEquals(1, $revision->user_id);
        $this->assertEquals(1, $revision->revisionable_id);
        $this->assertEquals('App\\Post', $revision->revisionable_type);
        $this->assertIsArray($revision->metadata);
        $this->assertEquals('value1', $revision->metadata['attribute1']);
    }

    #[Test]
    public function it_can_belong_to_a_user()
    {
        $this->makeModels();

        $revision = Revision::create([
            'user_id' => $this->user->id,
            'revisionable_id' => 1,
            'revisionable_type' => 'App\\Post',
            'metadata' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ],
        ]);

        $this->assertEquals($this->user->id, $revision->user->id);
    }

    #[Test]
    public function it_morphs_to_revisionable()
    {

        $this->makeModels();

        $revision = Revision::create([
            'user_id' => 1,
            'revisionable_id' => $this->post->id,
            'revisionable_type' => get_class($this->post),
            'metadata' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ],
        ]);

        $this->assertEquals($this->post->id, $revision->revisionable->id);
    }

    #[Test]
    public function it_filters_by_user()
    {

        $this->makeModels();

        $revision = Revision::create([
            'user_id' => $this->user->id,
            'revisionable_id' => 1,
            'revisionable_type' => 'App\\Post',
            'metadata' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ],
        ]);

        $revisions = Revision::whereUser($this->user)->get();

        $this->assertCount(1, $revisions);
        $this->assertEquals($revision->id, $revisions->first()->id);
    }

    #[Test]
    public function it_filters_by_revisionable()
    {
        $this->makeModels();

        $revision = Revision::create([
            'user_id' => 1,
            'revisionable_id' => $this->post->id,
            'revisionable_type' => get_class($this->post),
            'metadata' => [
                'attribute1' => 'value1',
                'attribute2' => 'value2',
            ],
        ]);

        $revisions = Revision::whereRevisionable($this->post->id, get_class($this->post))->get();

        $this->assertCount(1, $revisions);
        $this->assertEquals($revision->id, $revisions->first()->id);
    }
}
