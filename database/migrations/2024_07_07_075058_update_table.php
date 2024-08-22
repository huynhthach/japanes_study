<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Thêm cột topic_id
            $table->unsignedBigInteger('topic_id')->nullable()->after('published_at');
            
            // Thêm foreign key
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Xóa foreign key trước
            $table->dropForeign(['topic_id']);
            
            // Xóa cột topic_id
            $table->dropColumn('topic_id');
        });
    }
};
