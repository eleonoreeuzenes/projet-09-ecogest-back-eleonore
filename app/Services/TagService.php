<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Post;
use App\Models\TagPost;
use Illuminate\Support\Facades\DB;

use DateTime;

class TagService
{
    public function addTagsToPost(array $tags)
    {
        $allTags = Tag::get();
        $newTags = [];
        $tagsToAttach = [];
        
        foreach ($tags as $tag) {
            if (!in_array($tag, $allTags->pluck('label')->toArray())) {
                $newTag = Tag::create(['label' => $tag]);
                $tagsToAttach[] = $newTag->id;
            } else {
                $tagsToAttach[] = $allTags->where('label', $tag)->first()->id;
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

    public function updateTagsToPost(array $oldTags, array $updatedTags)
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
