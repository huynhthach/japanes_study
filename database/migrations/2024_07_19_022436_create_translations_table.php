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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Tên mô hình như 'Post', 'Course'
            $table->unsignedBigInteger('model_id'); // ID của mô hình
            $table->string('locale', 10); // Ngôn ngữ, ví dụ: 'en', 'vi'
            $table->string('field'); // Tên trường cần dịch như 'title', 'description'
            $table->text('value'); // Giá trị bản dịch
            $table->timestamps();
            
            // Đảm bảo không có bản sao cho cùng một mô hình, ID, ngôn ngữ và trường
            $table->unique(['model_type', 'model_id', 'locale', 'field']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
};
