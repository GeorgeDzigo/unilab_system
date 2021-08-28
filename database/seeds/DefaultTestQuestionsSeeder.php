<?php

use App\Models\TestQuestions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DefaultTestQuestionsSeeder extends Seeder
{
/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = [
            [
                'user_id' => null,
                'question_title' => 'სახელი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი სახლი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'first_name',
            ],
            [
                'user_id' => null,
                'question_title' => 'გვარი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი გვარი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'last_name'
            ],
            [
                'user_id' => null,
                'question_title' => 'ტელეფონის ნომერი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი ტელეფონის ნომერი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'phone_number'
            ],
            [
                'user_id' => null,
                'question_title' => 'მეილი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი მეილი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'email'
            ],
            [
                'user_id' => null,
                'question_title' => 'მისამართი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი მეილი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'address',
            ],
            [
                'user_id' => null,
                'question_title' => 'სამუშაო გამოცდილება',
                'question_title_explanation' => 'დაწერეთ თქვენი სამუშაო გამოცდილება',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'work_experience'
            ],
            [
                'user_id' => null,
                'question_title' => 'დაბადების თარიღი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი დაბადების თარიღი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'date',
                'option_type_key' => 'date',
                'unique_id' => 'birth_date',
            ],
            [
                'user_id' => null,
                'question_title' => 'პირადი ნომერი',
                'question_title_explanation' => 'შეიყვანეთ თქვენი პირადი ნომერი',
                'question_file' => null,
                'question_image' => null,
                'question_image_explanation' => null,
                'option_type' => 'Text answer',
                'option_type_key' => 'text_answer',
                'unique_id' => 'id_number',
            ]
        ];

        foreach($questions as $question)
            TestQuestions::updateOrCreate($question);

    }
}
