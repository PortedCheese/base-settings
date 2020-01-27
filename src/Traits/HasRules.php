<?php

namespace PortedCheese\BaseSettings\Traits;

use App\Role;
use App\RoleRule;

trait HasRules {

    public function makeRoles()
    {
        $editorRole = Role::query()
            ->where("name", "editor")
            ->first();

        if (empty($this->rules)) {
            return;
        }

        foreach ($this->rules as $rule) {
            if (empty($rule["slug"]) || empty($rule["title"]) || empty($rule["policy"])) {
                continue;
            }
            $policy = $rule["policy"];

            $model = RoleRule::query()
                ->where("slug", $rule["slug"])
                ->first();

            if (! $model) {
                try {
                    $model = RoleRule::create($rule);
                    $this->info("Model RoleRule generated ({$model->title} {$model->id})");
                }
                catch (\Exception $exception) {
                    $model = false;
                    $this->error("Failed to create a model");
                    continue;
                }
            }

            $class = "App\Policies\\" . $policy;
            if (
                $editorRole &&
                $model &&
                method_exists($class, "defaultRules") &&
                $this->confirm("Set default permissions for editor by $class?") &&
                is_numeric($class::defaultRules())
            ) {
                $exist = false;
                $rights = $class::defaultRules();
                foreach ($editorRole->rules as $item) {
                    if ($item->slug == $model->slug) {
                        $exist = true;
                        break;
                    }
                }
                if (! $exist) {
                    $editorRole->rules()->save($model, [
                        "rights" => $rights,
                    ]);
                    $this->info("Rules created");
                }
                else {
                    $editorRole->rules()->updateExistingPivot($model->id, [
                        "rights" => $rights,
                    ]);
                    $this->info("Rules updated");
                }
            }
        }
    }
}