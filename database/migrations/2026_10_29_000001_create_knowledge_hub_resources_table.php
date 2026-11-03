<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_hub_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('url');
            $table->text('description');
            $table->string('category'); // cybersecurity, web-dev, mobile-dev, ai-agents, cloud-devops, general-cs
            $table->string('resource_type')->default('tool'); // lab, course, documentation, tool, community
            $table->string('difficulty')->default('beginner'); // beginner, intermediate, advanced, all
            $table->string('pricing')->default('free'); // free, freemium, paid, varies
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_hub_resources');
    }
};
