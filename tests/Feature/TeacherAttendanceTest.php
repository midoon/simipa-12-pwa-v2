<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // semua middleware akan di-skip

    }

    public function setupDb()
    {
        $grade = Grade::create([
            'name' => 'kelas-test'
        ]);

        $group = Group::create([
            'grade_id' => $grade->id,
            'name' => 'Rombel Test'
        ]);

        Group::create([
            'grade_id' => $grade->id,
            'name' => 'Rombel Kosong'
        ]);

        $students = [];
        for ($i = 1; $i <= 2; $i++) {
            $students[] = Student::create([
                'name' => 'Siswa Test ' . $i,
                'group_id' => $group->id,
                'nisn' => '123456789' . $i,
                'gender' => "laki-laki",
            ]);
        }

        $activity = Activity::create([
            'name' => 'kbm',
            'description' => 'Kegiatan Belajar Mengajar'
        ]);

        return [
            'students' => $students,
            'group' => $group,
            'activity' => $activity
        ];
    }


    public function test_presensi_berhasil_dengan_dua_siswa()
    {
        $data = $this->setupDb();
        $presensi = [
            [
                'student_id' => $data['students'][0]->id,
                'status' => 'hadir',
                'activity_id' => $data['activity']->id,
                'group_id' => $data['group']->id,
                'day' => '2024-01-03'
            ],
            [
                'student_id' => $data['students'][1]->id,
                'status' => 'izin',
                'activity_id' => $data['activity']->id,
                'group_id' => $data['group']->id,
                'day' => '2024-01-03'
            ]
        ];

        $response = $this->post('/teacher/attendance/store', [
            'presensi' => $presensi
        ]);



        $response->assertJson([
            'message' => 'Presensi berhasil disimpan!'
        ]);

        foreach ($presensi as $p) {
            $this->assertDatabaseHas('attendances', [
                'student_id' => $p['student_id'],
                'status' => $p['status']
            ]);
        }
    }

    public function test_presensi_gagal_karena_sudah_dibuat()
    {
        $data = $this->setupDb(); // Ambil data ID yang pasti ada

        $presensi = [
            [
                'student_id' => $data['students'][0]->id,
                'status' => 'hadir',
                'activity_id' => $data['activity']->id,
                'group_id' => $data['group']->id,
                'day' => '2024-01-03'
            ],
            [
                'student_id' => $data['students'][1]->id,
                'status' => 'izin',
                'activity_id' => $data['activity']->id,
                'group_id' => $data['group']->id,
                'day' => '2024-01-03'
            ]
        ];

        // Simulasi request pertama, presensi berhasil disimpan
        $req1 = $this->post('/teacher/attendance/store', [
            'presensi' => $presensi
        ]);



        // Simulasi request kedua, seharusnya gagal karena data sudah ada
        $response = $this->post('/teacher/attendance/store', [
            'presensi' => $presensi
        ]);



        $response->assertJson([
            'message' => 'Presensi untuk kegiatan dan hari tersebut sudah dibuat!'
        ]);
    }



    public function test_presensi_kosong_tidak_dilakukan_perulangan()
    {
        $presensi = [];
        $response = $this->post('/teacher/attendance/store', [
            'presensi' => $presensi
        ]);

        $response->assertJson([
            'message' => 'Tidak ada daftar siswa'
        ]);
    }
}
