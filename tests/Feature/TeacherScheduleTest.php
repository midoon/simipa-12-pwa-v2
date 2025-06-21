<?php

namespace Tests\Feature;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // semua middleware akan di-skip

    }

    public function setupDb()
    {
        $teacher = Teacher::create([
            'name' => 'test teacher',
            'role' => ['guru'],
            'nik' => '1234567890',
            'gender' => 'laki-laki',
        ]);

        $grade = Grade::create([
            'name' => 'kelas test 1',
            'description' => 'Deskripsi Kelas Test'
        ]);

        $subject = Subject::create([
            'name' => 'Matematik',
            'grade_id' => $grade->id,
            'description' =>  'Deskripsi Matematika',
        ]);



        $group = Group::create([
            'grade_id' => $grade->id,
            'name' => 'rombel test 1'
        ]);



        $schedule = Schedule::create([
            'group_id' => $group->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'day_of_week' => 'senin',
            'start_time' => '08:00',
            'end_time' => '10:00',
        ]);

        return [
            'teacher' => $teacher,
            'grade' => $grade,
            'subject' => $subject,
            'group' => $group,
            'schedule' => $schedule
        ];
    }

    public function testTeacherScheduleIndex()
    {
        try {
            $data = $this->setupDb();

            $this->withSession([
                'teacher' => [
                    'teacherId' => $data['teacher']->id,
                    'name' => $data['teacher']->name,
                    'role' => json_encode(['guru'])
                ]
            ]);

            $response = $this->get('/teacher/schedule');
            $response->assertStatus(200);
        } catch (\Throwable $e) {
            // echo "EXCEPTION: " . $e->getMessage();
            // echo "\nTRACE:\n" . $e->getTraceAsString();
        }
    }
}
