<?php

namespace PhotoContainer\PhotoContainer\Contexts\Search\Persistence;

use PhotoContainer\PhotoContainer\Contexts\Search\Domain\Tag;
use PhotoContainer\PhotoContainer\Contexts\Search\Domain\TagRepository;
use PhotoContainer\PhotoContainer\Infrastructure\Persistence\Eloquent\TagCategory;

class EloquentTagRepository implements TagRepository
{
    public function findAll(): array
    {
        $all = TagCategory::all()->load('tags');

        $all = $all->map(function ($item, $key) {
            $tags = [];
            $tags['tag_group']['description'] = $item->description;
            $tags['tag_group']['id'] = $item->id;

            $tags['tag_group']['list'] = $item->tags->map(function ($item, $key) {
                return new Tag($item->id, $item->description);
            });

            return $tags;
        })->toArray();

        return $all;
    }
}
