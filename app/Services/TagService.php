<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Post;
use App\Models\TagPost;
use Illuminate\Support\Facades\DB;

use DateTime;

class TagService
{
    public static function addTagsToPost(array $tags)
    {
        $allTags = Tag::get();
        $newTags = [];
        $tagsToAttach = [];
        
        foreach ($tags as $tag) {
            if (!in_array($tag['label'], $allTags->pluck('label')->toArray())) {
                $newTag = Tag::create(['label' => $tag['label']]);
                $tagsToAttach[] = $newTag->id;
            } else {
                $tagsToAttach[] = $allTags->where('label', $tag['label'])->first()->id;
            }
        }
        
        if (count($newTags) > 0) {
            
            DB::transaction(function () {
                foreach ($newTag as $newTags) {
                    $tag = Tag::create([
                        'label' => $label,
                    ]);
            
                    $tag->save();
                    $tagsToAttach[] = $tag;
                }
            });
        }

        return $tagsToAttach;
    }

    public static function updateTagsToPost(array $oldTags, array $updatedTags)
    {
        $tagsToAttach = addTagsToPost($updatedTags);
        $tagsToDetach = [];
        
        foreach ($oldTags as $tag) {
            if (!in_array($tag, $tagsToAttach)) {
                $tagsToDetach[] = $tag;
            }
        }

        $tagsToUpdate = array('attach' => $tagsToAttach, 'detach' => $tagsToDetach);

        return $tagsToUpdate;
    }

}
