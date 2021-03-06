<?php


namespace App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\BaseController;
use App\Includes\Constant;
use App\Models\LevelOneGroup;
use App\Models\LevelThreeGroup;
use App\Models\LevelTwoGroup;
use Illuminate\Http\Request;


class GroupsController extends BaseController
{
    public function createLevelOneGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");

        // check title
        if(LevelOneGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $group = new LevelOneGroup();
        $group->title = $title;
        $group->type = $type;
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['g1_id' => $group->id]);
    }

    public function createLevelTwoGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");

        $levelOneGroup = LevelOneGroup::find($request->input("g1_id"));

        // check title
        if(LevelTwoGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        // check father group
        if(!$levelOneGroup)
            return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        $group = new LevelTwoGroup();
        $group->title = $title;
        $group->type = $type;
        $levelOneGroup->level_two_groups()->save($group);
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['g2' => $group->id]);
    }

    public function createLevelThreeGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");

        $levelTwoGroup = LevelTwoGroup::find($request->input("g2_id"));

        // check title
        if(LevelThreeGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        // check father group
        if(!$levelTwoGroup)
            return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        $group = new LevelThreeGroup();
        $group->title = $title;
        $group->type = $type;
        $levelTwoGroup->level_three_groups()->save($group);
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS,  ['g3' => $group->id]);
    }

    public function editLevelOneGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");
        $group = LevelOneGroup::find($request->input("id"));

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);


        // check title
        if($group->title != $title && LevelOneGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $group->title = $title;
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editLevelTwoGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");
        $group = LevelTwoGroup::find($request->input("id"));

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        // check title
        if($group->title != $title && LevelTwoGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $group->title = $title;
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function editLevelThreeGroup(Request $request){
        $title = $request->input("title");
        $type = $request->input("type");
        $group = LevelThreeGroup::find($request->input("id"));

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        // check title
        if($group->title != $title && LevelThreeGroup::type($type)->where('title', $title)->exists())
            return $this->sendResponse(Constant::$REPETITIVE_TITLE, null);

        $group->title = $title;
        $group->save();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteLevelOneGroup(Request $request){
        $group = LevelOneGroup::find($request->input("id"));
        $force_delete = $request->input("force_delete");

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        if(!$force_delete && $group->courses->count() > 0)
            return $this->sendResponse(Constant::$RELATED_ENTITIES, null);

        foreach ($group->courses as $course){
            $course->level_one_group()->dissociate();
            $course->level_two_group()->dissociate();
            $course->level_three_group()->dissociate();
            $course->save();
        }

        foreach ($group->posts as $post){
            $post->level_one_group()->dissociate();
            $post->level_two_group()->dissociate();
            $post->level_three_group()->dissociate();
            $post->save();
        }

        // delete cascade
        $group->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteLevelTwoGroup(Request $request){
        $group = LevelTwoGroup::find($request->input("id"));
        $force_delete = $request->input("force_delete");

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        if(!$force_delete && $group->courses->count() > 0)
            return $this->sendResponse(Constant::$RELATED_ENTITIES, null);

        foreach ($group->courses as $course){
            $course->level_two_group()->dissociate();
            $course->level_three_group()->dissociate();
            $course->save();
        }

        foreach ($group->posts as $post){
            $post->level_two_group()->dissociate();
            $post->level_three_group()->dissociate();
            $post->save();
        }

        // delete cascade
        $group->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public function deleteLevelThreeGroup(Request $request){
        $group = LevelThreeGroup::find($request->input("id"));
        $force_delete = $request->input("force_delete");

        if(!$group) return $this->sendResponse(Constant::$ENTITY_NOT_FOUND, null);

        if(!$force_delete && $group->courses->count() > 0)
            return $this->sendResponse(Constant::$RELATED_ENTITIES, null);

        foreach ($group->courses as $course){
            $course->level_three_group()->dissociate();
            $course->save();
        }

        foreach ($group->posts as $post){
            $post->level_three_group()->dissociate();
            $post->save();
        }

        $group->delete();

        return $this->sendResponse(Constant::$SUCCESS, null);
    }

    public static function checkGroupsHierarchy($groups){
        if(isset($groups->g1) && $groups->g1 != null){
            $g1 = LevelOneGroup::find($groups->g1);
            if(isset($groups->g2) && $groups->g2 != null){
                $g2 = LevelTwoGroup::find($groups->g2);
                if (!$g1->level_two_groups->contains($groups->g2))
                    return false;
                else if(isset($groups->g3) && $groups->g3 != null){
                    if (!$g2->level_three_groups->contains($groups->g3))
                        return false;
                }
            }
        }

        if($groups->g2 && !$groups->g1)
            return false;

        if($groups->g3 && (!$groups->g1 || !$groups->g2))
            return false;

        return true;
    }

    public function fetchGroups($type){
        $groups = LevelOneGroup::type($type)->get()->map(function ($level_one_group){
            $level_two_groups = $level_one_group->level_two_groups->map(function ($level_two_group){
                $level_three_groups = $level_two_group->level_three_groups->map(function ($level_three_group){
                    return [
                        'level' => 3,
                        'id' => $level_three_group->id,
                        'title' => $level_three_group->title
                    ];
                });

                return [
                    'level' => 2,
                    'id' => $level_two_group->id,
                    'title' => $level_two_group->title,
                    'groups' => $level_three_groups
                ];
            });

            return [
                'level' => 1,
                'id' => $level_one_group->id,
                'title' => $level_one_group->title,
                'groups' => $level_two_groups
            ];
        });

        return $this->sendResponse(Constant::$SUCCESS, $groups);
    }
}
