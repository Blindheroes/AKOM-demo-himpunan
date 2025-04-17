<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Department;
use App\Models\Event;
use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\LpjTemplate;
use App\Models\Lpj;
use App\Models\News;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\GalleryImages;
use App\Models\Signature;
use App\Models\OAuthProvider;
use App\Models\LetterNumberFormat;
use App\Models\FinancialTransaction;
use App\Models\EventRegistration;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear storage directories
        Storage::deleteDirectory('public/signatures');
        Storage::deleteDirectory('public/documents');
        Storage::deleteDirectory('public/images');
        Storage::deleteDirectory('public/events');
        Storage::deleteDirectory('public/galleries');
        
        // Recreate directories
        Storage::makeDirectory('public/signatures');
        Storage::makeDirectory('public/documents');
        Storage::makeDirectory('public/images');
        Storage::makeDirectory('public/events');
        Storage::makeDirectory('public/galleries');
        
        // Create Users
        $this->createUsers();
        
        // Create Departments
        $this->createDepartments();
        
        // Assign Department Heads
        $this->assignDepartmentHeads();
        
        // Create Signatures
        $this->createSignatures();
        
        // Create Letter Templates
        $this->createLetterTemplates();
        
        // Create Letter Number Formats
        $this->createLetterNumberFormats();
        
        // Create Letters
        $this->createLetters();
        
        // Create LPJ Templates
        $this->createLpjTemplates();
        
        // Create Events
        $this->createEvents();
        
        // Create Event Registrations
        $this->createEventRegistrations();
        
        // Create LPJs
        $this->createLpjs();
        
        // Create Financial Transactions
        $this->createFinancialTransactions();
        
        // Create News
        $this->createNews();
        
        // Create Documents
        $this->createDocuments();
        
        // Create Galleries
        $this->createGalleries();
    }
    
    private function createUsers()
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@himatekom.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nim' => '000000001',
            'role' => 'admin',
            'is_active' => true,
            'signature_authority' => true,
        ]);
        
        // Executive Users (Chair, Vice Chair, Secretary, Treasurer)
        User::create([
            'name' => 'Chairperson',
            'email' => 'chair@himatekom.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nim' => '190123001',
            'role' => 'executive',
            'is_active' => true,
            'signature_authority' => true,
        ]);
        
        User::create([
            'name' => 'Vice Chairperson',
            'email' => 'vicechair@himatekom.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nim' => '190123002',
            'role' => 'executive',
            'is_active' => true,
            'signature_authority' => true,
        ]);
        
        User::create([
            'name' => 'Secretary',
            'email' => 'secretary@himatekom.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nim' => '190123003',
            'role' => 'executive',
            'is_active' => true,
            'signature_authority' => true,
        ]);
        
        User::create([
            'name' => 'Treasurer',
            'email' => 'treasurer@himatekom.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nim' => '190123004',
            'role' => 'executive',
            'is_active' => true,
            'signature_authority' => true,
        ]);
        
        // Staff Users (Department Heads)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Department Head $i",
                'email' => "dept$i@himatekom.org",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'nim' => "190123" . str_pad($i + 10, 3, '0', STR_PAD_LEFT),
                'role' => 'staff',
                'is_active' => true,
                'signature_authority' => true,
            ]);
        }
        
        // Regular Staff
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Staff Member $i",
                'email' => "staff$i@himatekom.org",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'nim' => "200123" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'role' => 'staff',
                'is_active' => true,
                'signature_authority' => false,
            ]);
        }
        
        // Members
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "Regular Member $i",
                'email' => "member$i@himatekom.org",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'nim' => "210123" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'role' => 'member',
                'is_active' => true,
                'signature_authority' => false,
            ]);
        }
    }
    
    private function createDepartments()
    {
        // Main Organization (Parent Department)
        $mainDept = Department::create([
            'name' => 'HIMATEKOM',
            'slug' => 'himatekom',
            'description' => 'Himpunan Mahasiswa Teknik Komputer',
            'is_active' => true,
        ]);
        
        // Child Departments
        $depts = [
            [
                'name' => 'Education and Development',
                'slug' => 'education-development',
                'description' => 'Responsible for academic and professional development activities'
            ],
            [
                'name' => 'Information and Communication',
                'slug' => 'information-communication',
                'description' => 'Manages information distribution and communication'
            ],
            [
                'name' => 'Research and Technology',
                'slug' => 'research-technology',
                'description' => 'Focuses on research projects and technological innovation'
            ],
            [
                'name' => 'Social and Community Service',
                'slug' => 'social-community',
                'description' => 'Organizes social and community service events'
            ],
            [
                'name' => 'Events and Activities',
                'slug' => 'events-activities',
                'description' => 'Plans and executes organizational events and activities'
            ]
        ];
        
        foreach ($depts as $index => $dept) {
            Department::create([
                'name' => $dept['name'],
                'slug' => $dept['slug'],
                'description' => $dept['description'],
                'parent_id' => $mainDept->id,
                'is_active' => true,
            ]);
        }
    }
    
    private function assignDepartmentHeads()
    {
        // Main organization heads
        $mainDept = Department::where('slug', 'himatekom')->first();
        $mainDept->head_id = User::where('email', 'chair@himatekom.org')->first()->id;
        $mainDept->save();
        
        // Department heads
        $departments = Department::where('slug', '!=', 'himatekom')->get();
        $staffUsers = User::where('role', 'staff')
            ->where('email', 'like', 'dept%')
            ->get();
        
        foreach ($departments as $index => $dept) {
            if (isset($staffUsers[$index])) {
                $dept->head_id = $staffUsers[$index]->id;
                $staffUsers[$index]->department_id = $dept->id;
                
                $dept->save();
                $staffUsers[$index]->save();
            }
        }
        
        // Assign regular staff to departments
        $regularStaff = User::where('role', 'staff')
            ->where('email', 'like', 'staff%')
            ->get();
        
        foreach ($regularStaff as $index => $staff) {
            $deptIndex = $index % count($departments);
            $staff->department_id = $departments[$deptIndex]->id;
            $staff->save();
        }
        
        // Assign members to departments randomly
        $members = User::where('role', 'member')->get();
        
        foreach ($members as $member) {
            $randomDept = $departments->random();
            $member->department_id = $randomDept->id;
            $member->save();
        }
    }
    
    private function createSignatures()
    {
        $signatoryUsers = User::where('signature_authority', true)->get();
        
        foreach ($signatoryUsers as $user) {
            // For testing purposes, we'll just create placeholder entries
            // In a real scenario, you would store actual signature images
            Signature::create([
                'user_id' => $user->id,
                'signature_path' => 'public/signatures/signature_' . $user->id . '.png',
                'is_active' => true,
            ]);
        }
    }
    
    private function createLetterTemplates()
    {
        $admin = User::where('role', 'admin')->first();
        
        $templates = [
            [
                'name' => 'Official Invitation',
                'slug' => 'official-invitation',
                'type' => 'invitation',
                'description' => 'Template for official event invitations',
                'content' => "<div class='letterhead'>[LOGO]</div>
                <h1>INVITATION</h1>
                <p>Letter Number: [LETTER_NUMBER]</p>
                <p>Date: [DATE]</p>
                <p>To: [RECIPIENT]<br>[RECIPIENT_POSITION]<br>[RECIPIENT_INSTITUTION]</p>
                <p>Subject: [REGARDING]</p>
                <p>Dear [RECIPIENT],</p>
                <p>We are pleased to invite you to [EVENT_NAME] that will be held:</p>
                <p>Date: [EVENT_DATE]<br>Time: [EVENT_TIME]<br>Location: [EVENT_LOCATION]</p>
                <p>We look forward to your attendance.</p>
                <p>Sincerely,</p>
                <p>[SENDER_NAME]<br>[SENDER_POSITION]<br>HIMATEKOM</p>",
                'format' => json_encode([
                    'paper_size' => 'A4',
                    'margin_top' => '2cm',
                    'margin_right' => '2cm',
                    'margin_bottom' => '2cm',
                    'margin_left' => '2cm',
                    'font_family' => 'Times New Roman',
                    'font_size' => '12pt',
                ]),
            ],
            [
                'name' => 'Formal Request',
                'slug' => 'formal-request',
                'type' => 'request',
                'description' => 'Template for formal requests to external parties',
                'content' => "<div class='letterhead'>[LOGO]</div>
                <h1>FORMAL REQUEST</h1>
                <p>Letter Number: [LETTER_NUMBER]</p>
                <p>Date: [DATE]</p>
                <p>To: [RECIPIENT]<br>[RECIPIENT_POSITION]<br>[RECIPIENT_INSTITUTION]</p>
                <p>Subject: [REGARDING]</p>
                <p>Dear [RECIPIENT],</p>
                <p>We would like to formally request [REQUEST_DETAILS].</p>
                <p>The purpose of this request is [REQUEST_PURPOSE].</p>
                <p>We appreciate your consideration and look forward to your positive response.</p>
                <p>Sincerely,</p>
                <p>[SENDER_NAME]<br>[SENDER_POSITION]<br>HIMATEKOM</p>",
                'format' => json_encode([
                    'paper_size' => 'A4',
                    'margin_top' => '2cm',
                    'margin_right' => '2cm',
                    'margin_bottom' => '2cm',
                    'margin_left' => '2cm',
                    'font_family' => 'Times New Roman',
                    'font_size' => '12pt',
                ]),
            ],
            [
                'name' => 'Official Statement',
                'slug' => 'official-statement',
                'type' => 'statement',
                'description' => 'Template for official statements',
                'content' => "<div class='letterhead'>[LOGO]</div>
                <h1>OFFICIAL STATEMENT</h1>
                <p>Letter Number: [LETTER_NUMBER]</p>
                <p>Date: [DATE]</p>
                <p>To: [RECIPIENT]<br>[RECIPIENT_POSITION]<br>[RECIPIENT_INSTITUTION]</p>
                <p>Subject: [REGARDING]</p>
                <p>We, the undersigned, hereby state that [STATEMENT_CONTENT]</p>
                <p>This statement is made with full awareness and responsibility.</p>
                <p>Sincerely,</p>
                <p>[SENDER_NAME]<br>[SENDER_POSITION]<br>HIMATEKOM</p>",
                'format' => json_encode([
                    'paper_size' => 'A4',
                    'margin_top' => '2cm',
                    'margin_right' => '2cm',
                    'margin_bottom' => '2cm',
                    'margin_left' => '2cm',
                    'font_family' => 'Times New Roman',
                    'font_size' => '12pt',
                ]),
            ],
        ];
        
        foreach ($templates as $template) {
            LetterTemplate::create([
                'name' => $template['name'],
                'slug' => $template['slug'],
                'type' => $template['type'],
                'description' => $template['description'],
                'content' => $template['content'],
                'format' => $template['format'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }
    }
    
    private function createLetterNumberFormats()
    {
        $departments = Department::all();
        
        foreach ($departments as $dept) {
            LetterNumberFormat::create([
                'department_id' => $dept->id,
                'format_pattern' => '{NUMBER}/HIMATEKOM/{DEPT}/{MONTH}/{YEAR}',
                'next_number' => 1,
                'reset_period' => 'yearly',
                'is_active' => true,
            ]);
        }
    }
    
    private function createLetters()
    {
        $templates = LetterTemplate::all();
        $mainDept = Department::where('slug', 'himatekom')->first();
        $chair = User::where('email', 'chair@himatekom.org')->first();
        $secretary = User::where('email', 'secretary@himatekom.org')->first();
        
        $letters = [
            [
                'title' => 'Invitation to Annual Meeting',
                'regarding' => 'Annual Organizational Meeting',
                'recipient' => 'All HIMATEKOM Members',
                'recipient_position' => 'Students',
                'recipient_institution' => 'University',
                'content' => "Dear Members,\n\nWe cordially invite you to attend our Annual Organizational Meeting that will discuss plans and strategies for the upcoming academic year. Your attendance and participation are highly appreciated.\n\nDetails:\nDate: May 25, 2025\nTime: 14:00 - 16:00\nLocation: Auditorium Building A\n\nSincerely,\nHIMATEKOM Board",
                'status' => 'draft',
            ],
            [
                'title' => 'Request for Venue Reservation',
                'regarding' => 'Venue Reservation for Tech Exhibition',
                'recipient' => 'Facility Manager',
                'recipient_position' => 'Administrative Staff',
                'recipient_institution' => 'University',
                'content' => "Dear Facility Manager,\n\nWe would like to request the reservation of the Main Hall for our upcoming Technology Exhibition event. The event aims to showcase student projects and innovations in the field of computer engineering.\n\nDetails:\nDate: June 10-11, 2025\nTime: 09:00 - 17:00\nExpected Attendance: 200-250 people\n\nWe appreciate your consideration and look forward to your positive response.\n\nSincerely,\nHIMATEKOM Event Coordinator",
                'status' => 'signed',
            ],
            [
                'title' => 'Statement of Collaboration',
                'regarding' => 'Collaboration for Programming Competition',
                'recipient' => 'Computer Science Student Association',
                'recipient_position' => 'Partner Organization',
                'recipient_institution' => 'University',
                'content' => "Dear Partners,\n\nThis letter serves as an official statement of our intention to collaborate on the upcoming Inter-University Programming Competition. Both our organizations agree to jointly organize, promote, and execute the event with shared responsibilities and resources.\n\nThe competition will be held on July 15, 2025, at the University Computing Center. Both parties will commit to providing volunteers, technical support, and prizes for the winners.\n\nWe look forward to a successful collaboration.\n\nSincerely,\nHIMATEKOM Chair",
                'status' => 'sent',
            ],
        ];
        
        foreach ($letters as $index => $letterData) {
            $template = $templates[$index % count($templates)];
            $date = now()->addDays($index * 5);
            
            $letter = Letter::create([
                'title' => $letterData['title'],
                'template_id' => $template->id,
                'letter_number' => ($index + 1) . '/HIMATEKOM/MAIN/04/2025',
                'date' => $date,
                'regarding' => $letterData['regarding'],
                'recipient' => $letterData['recipient'],
                'recipient_position' => $letterData['recipient_position'],
                'recipient_institution' => $letterData['recipient_institution'],
                'content' => $letterData['content'],
                'status' => $letterData['status'],
                'department_id' => $mainDept->id,
                'created_by' => $secretary->id,
            ]);
            
            if ($letterData['status'] === 'signed' || $letterData['status'] === 'sent') {
                $letter->signed_by = $chair->id;
                $letter->signing_date = $date;
                $letter->save();
            }
        }
    }
    
    private function createLpjTemplates()
    {
        $admin = User::where('role', 'admin')->first();
        
        $templates = [
            [
                'name' => 'General Event Report',
                'slug' => 'general-event-report',
                'description' => 'Template for general event reports',
                'structure' => json_encode([
                    'sections' => [
                        [
                            'title' => 'Event Information',
                            'fields' => [
                                ['name' => 'event_name', 'type' => 'text', 'label' => 'Event Name', 'required' => true],
                                ['name' => 'event_date', 'type' => 'date', 'label' => 'Event Date', 'required' => true],
                                ['name' => 'event_location', 'type' => 'text', 'label' => 'Event Location', 'required' => true],
                                ['name' => 'event_organizer', 'type' => 'text', 'label' => 'Organizing Department', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Background and Objectives',
                            'fields' => [
                                ['name' => 'background', 'type' => 'textarea', 'label' => 'Background', 'required' => true],
                                ['name' => 'objectives', 'type' => 'textarea', 'label' => 'Objectives', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Implementation',
                            'fields' => [
                                ['name' => 'implementation_summary', 'type' => 'textarea', 'label' => 'Implementation Summary', 'required' => true],
                                ['name' => 'participants', 'type' => 'number', 'label' => 'Number of Participants', 'required' => true],
                                ['name' => 'timeline', 'type' => 'textarea', 'label' => 'Timeline of Activities', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Financial Report',
                            'fields' => [
                                ['name' => 'budget_allocation', 'type' => 'number', 'label' => 'Budget Allocation', 'required' => true],
                                ['name' => 'total_expenses', 'type' => 'number', 'label' => 'Total Expenses', 'required' => true],
                                ['name' => 'balance', 'type' => 'number', 'label' => 'Balance', 'required' => true],
                                ['name' => 'financial_details', 'type' => 'textarea', 'label' => 'Financial Details', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Evaluation',
                            'fields' => [
                                ['name' => 'achievements', 'type' => 'textarea', 'label' => 'Achievements', 'required' => true],
                                ['name' => 'challenges', 'type' => 'textarea', 'label' => 'Challenges Faced', 'required' => true],
                                ['name' => 'recommendations', 'type' => 'textarea', 'label' => 'Recommendations for Future Events', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Conclusion',
                            'fields' => [
                                ['name' => 'conclusion', 'type' => 'textarea', 'label' => 'Conclusion', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Attachments',
                            'fields' => [
                                ['name' => 'attachments', 'type' => 'textarea', 'label' => 'List of Attachments', 'required' => false]
                            ]
                        ]
                    ]
                ]),
            ],
            [
                'name' => 'Workshop Report',
                'slug' => 'workshop-report',
                'description' => 'Template for workshop or training event reports',
                'structure' => json_encode([
                    'sections' => [
                        [
                            'title' => 'Workshop Information',
                            'fields' => [
                                ['name' => 'workshop_title', 'type' => 'text', 'label' => 'Workshop Title', 'required' => true],
                                ['name' => 'workshop_date', 'type' => 'date', 'label' => 'Date', 'required' => true],
                                ['name' => 'workshop_location', 'type' => 'text', 'label' => 'Location', 'required' => true],
                                ['name' => 'workshop_duration', 'type' => 'text', 'label' => 'Duration', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Workshop Content',
                            'fields' => [
                                ['name' => 'topic', 'type' => 'text', 'label' => 'Main Topic', 'required' => true],
                                ['name' => 'speakers', 'type' => 'textarea', 'label' => 'Speakers/Trainers', 'required' => true],
                                ['name' => 'materials', 'type' => 'textarea', 'label' => 'Materials Covered', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Participants',
                            'fields' => [
                                ['name' => 'target_participants', 'type' => 'textarea', 'label' => 'Target Participants', 'required' => true],
                                ['name' => 'actual_participants', 'type' => 'number', 'label' => 'Number of Actual Participants', 'required' => true],
                                ['name' => 'participant_feedback', 'type' => 'textarea', 'label' => 'Participant Feedback Summary', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Financial Report',
                            'fields' => [
                                ['name' => 'budget', 'type' => 'number', 'label' => 'Budget', 'required' => true],
                                ['name' => 'expenses', 'type' => 'textarea', 'label' => 'Expense Details', 'required' => true],
                                ['name' => 'balance', 'type' => 'number', 'label' => 'Balance', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Evaluation',
                            'fields' => [
                                ['name' => 'successes', 'type' => 'textarea', 'label' => 'Successes', 'required' => true],
                                ['name' => 'challenges', 'type' => 'textarea', 'label' => 'Challenges', 'required' => true],
                                ['name' => 'improvements', 'type' => 'textarea', 'label' => 'Areas for Improvement', 'required' => true]
                            ]
                        ],
                        [
                            'title' => 'Conclusion and Follow-up',
                            'fields' => [
                                ['name' => 'conclusion', 'type' => 'textarea', 'label' => 'Conclusion', 'required' => true],
                                ['name' => 'follow_up', 'type' => 'textarea', 'label' => 'Follow-up Actions', 'required' => true]
                            ]
                        ]
                    ]
                ]),
            ],
        ];
        
        foreach ($templates as $template) {
            LpjTemplate::create([
                'name' => $template['name'],
                'slug' => $template['slug'],
                'description' => $template['description'],
                'structure' => $template['structure'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }
    }
    
    private function createEvents()
    {
        $departments = Department::all();
        $organizer = User::where('role', 'executive')->first();
        $approver = User::where('email', 'chair@himatekom.org')->first();
        
        $now = now();
        
        $events = [
            [
                'title' => 'Annual General Meeting',
                'slug' => 'annual-general-meeting-2025',
                'description' => 'Annual general meeting for all HIMATEKOM members',
                'content' => "The Annual General Meeting (AGM) is the main organizational meeting where all members gather to discuss the achievements of the past year and plans for the coming year. This meeting also includes the presentation of annual reports, financial statements, and election of new board members when applicable. All members are encouraged to attend and participate in the decision-making process.",
                'start_date' => $now->copy()->addDays(30),
                'end_date' => $now->copy()->addDays(30)->addHours(3),
                'location' => 'Auditorium Building A',
                'status' => 'published',
                'max_participants' => 200,
                'registration_deadline' => $now->copy()->addDays(25),
                'is_featured' => true,
                'budget' => 1500000,
            ],
            [
                'title' => 'Technical Workshop: Web Development',
                'slug' => 'technical-workshop-web-dev-2025',
                'description' => 'Hands-on workshop on modern web development technologies',
                'content' => "This technical workshop focuses on modern web development technologies and best practices. Participants will learn about HTML5, CSS3, JavaScript, and popular frameworks. The workshop includes both theoretical sessions and hands-on practice. By the end of the workshop, participants will have built a simple but functional web application. This workshop is suitable for beginners and intermediate level developers.",
                'start_date' => $now->copy()->addDays(15),
                'end_date' => $now->copy()->addDays(15)->addHours(8),
                'location' => 'Computer Lab 2',
                'status' => 'published',
                'max_participants' => 50,
                'registration_deadline' => $now->copy()->addDays(10),
                'is_featured' => true,
                'budget' => 3000000,
            ],
            [
                'title' => 'Community Service: Computer Literacy for Elementary Schools',
                'slug' => 'community-service-computer-literacy-2025',
                'description' => 'Volunteer program to teach basic computer skills to elementary school students',
                'content' => "This community service program aims to introduce basic computer literacy to elementary school students in underserved areas. Our members will volunteer to teach fundamental computer skills, internet safety, and basic software usage. The program helps bridge the digital divide and inspires young students to explore technology. Volunteers will be organized in teams and provided with teaching materials and guidelines.",
                'start_date' => $now->copy()->addDays(45),
                'end_date' => $now->copy()->addDays(45)->addHours(6),
                'location' => 'Various Elementary Schools',
                'status' => 'published',
                'max_participants' => 30,
                'registration_deadline' => $now->copy()->addDays(40),
                'is_featured' => false,
                'budget' => 2000000,
            ],
            [
                'title' => 'Tech Competition: Coding Challenge',
                'slug' => 'tech-competition-coding-challenge-2025',
                'description' => 'Annual coding competition for university students',
                'content' => "The Annual Coding Challenge is our prestigious programming competition open to all university students. Participants will solve algorithmic problems and coding challenges within a time limit. The competition tests problem-solving skills, algorithm knowledge, and coding efficiency. Prizes will be awarded to the top performers. This event helps foster a competitive spirit and enhances programming skills among students.",
                'start_date' => $now->copy()->addDays(60),
                'end_date' => $now->copy()->addDays(60)->addHours(10),
                'location' => 'University Computing Center',
                'status' => 'published',
                'max_participants' => 100,
                'registration_deadline' => $now->copy()->addDays(55),
                'is_featured' => true,
                'budget' => 5000000,
            ],
            [
                'title' => 'Past Event: Orientation for New Members',
                'slug' => 'orientation-new-members-2025',
                'description' => 'Orientation program for newly joined organization members',
                'content' => "The New Member Orientation is designed to welcome and integrate new members into our organization. The program includes introduction to the organization's structure, values, and activities. New members will meet current members and board members, learn about different departments, and understand how they can contribute. The orientation also includes team-building activities to foster camaraderie among new members.",
                'start_date' => $now->copy()->subDays(15),
                'end_date' => $now->copy()->subDays(15)->addHours(5),
                'location' => 'Student Center Hall',
                'status' => 'completed',
                'max_participants' => 75,
                'registration_deadline' => $now->copy()->subDays(20),
                'is_featured' => false,
                'budget' => 1000000,
            ],
        ];
        
        foreach ($events as $index => $eventData) {
            $department = $departments[$index % count($departments)];
            
            Event::create([
                'title' => $eventData['title'],
                'slug' => $eventData['slug'],
                'description' => $eventData['description'],
                'content' => $eventData['content'],
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'location' => $eventData['location'],
                'organizer_id' => $organizer->id,
                'department_id' => $department->id,
                'status' => $eventData['status'],
                'max_participants' => $eventData['max_participants'],
                'registration_deadline' => $eventData['registration_deadline'],
                'is_featured' => $eventData['is_featured'],
                'budget' => $eventData['budget'],
                'created_by' => $organizer->id,
                'approved_by' => $approver->id,
            ]);
        }
    }
    
    private function createEventRegistrations()
    {
        $events = Event::all();
        $members = User::where('role', 'member')->get();
        
        foreach ($events as $event) {
            // Skip past events
            if ($event->status === 'completed') {
                continue;
            }
            
            // Register some members to each event
            $registerCount = min(rand(5, 15), $members->count());
            $registeredMembers = $members->random($registerCount);
            
            foreach ($registeredMembers as $member) {
                $registrationDate = now()->subDays(rand(1, 5));
                
                // Set different statuses for variety
                $statuses = ['pending', 'approved', 'approved', 'approved'];
                $status = $statuses[array_rand($statuses)];
                
                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $member->id,
                    'registration_date' => $registrationDate,
                    'status' => $status,
                    'payment_status' => $event->budget > 0 ? 'paid' : 'unpaid',
                    'notes' => $status === 'approved' ? 'Registration confirmed' : null,
                ]);
            }
        }
    }
    
    private function createLpjs()
    {
        $completedEvents = Event::where('status', 'completed')->get();
        $templates = LpjTemplate::all();
        $treasurer = User::where('email', 'treasurer@himatekom.org')->first();
        $chair = User::where('email', 'chair@himatekom.org')->first();
        
        foreach ($completedEvents as $event) {
            $template = $templates->random();
            
            // Create LPJ with template structure filled with sample data
            $lpj = new Lpj();
            $lpj->title = $event->title . ' - Event Report';
            $lpj->event_id = $event->id;
            $lpj->template_id = $template->id;
            $lpj->status = 'approved';
            $lpj->version = 1;
            $lpj->created_by = $treasurer->id;
            $lpj->approved_by = $chair->id;
            $lpj->approval_date = now()->subDays(rand(1, 5));
            
            // Create structured content based on template
            $templateStructure = json_decode($template->structure, true);
            $content = [];
            
            foreach ($templateStructure['sections'] as $section) {
                $sectionData = [];
                
                foreach ($section['fields'] as $field) {
                    $fieldName = $field['name'];
                    
                    switch ($fieldName) {
                        case 'event_name':
                        case 'workshop_title':
                            $sectionData[$fieldName] = $event->title;
                            break;
                        case 'event_date':
                        case 'workshop_date':
                            $sectionData[$fieldName] = $event->start_date->format('Y-m-d');
                            break;
                        case 'event_location':
                        case 'workshop_location':
                            $sectionData[$fieldName] = $event->location;
                            break;
                        case 'event_organizer':
                            $sectionData[$fieldName] = $event->department->name;
                            break;
                        case 'background':
                            $sectionData[$fieldName] = "This event was organized as part of our annual program to " . substr($event->description, 0, 100);
                            break;
                        case 'objectives':
                            $sectionData[$fieldName] = "The main objectives of this event were to:\n1. Increase member engagement\n2. Develop technical skills\n3. Foster community involvement";
                            break;
                        case 'participants':
                        case 'actual_participants':
                            $sectionData[$fieldName] = rand(30, $event->max_participants);
                            break;
                        case 'budget_allocation':
                        case 'budget':
                            $sectionData[$fieldName] = $event->budget;
                            break;
                        case 'total_expenses':
                        case 'expenses':
                            $expenses = $event->budget * (rand(80, 95) / 100);
                            $sectionData[$fieldName] = $field['type'] === 'number' ? $expenses : "Total expenses: Rp " . number_format($expenses, 0, ',', '.');
                            break;
                        case 'balance':
                            $balance = $event->budget - ($event->budget * (rand(80, 95) / 100));
                            $sectionData[$fieldName] = $balance;
                            break;
                        default:
                            if ($field['type'] === 'textarea') {
                                $sectionData[$fieldName] = "Sample content for " . $field['label'] . ". This is placeholder text that would be filled with actual data during real LPJ creation.";
                            } elseif ($field['type'] === 'number') {
                                $sectionData[$fieldName] = rand(1, 100) * 10000;
                            } elseif ($field['type'] === 'text') {
                                $sectionData[$fieldName] = "Sample " . $field['label'];
                            }
                            break;
                    }
                }
                
                $content[$section['title']] = $sectionData;
            }
            
            $lpj->content = json_encode($content);
            $lpj->save();
        }
    }
    
    private function createFinancialTransactions()
    {
        $events = Event::all();
        $lpjs = Lpj::all();
        $departments = Department::all();
        $treasurer = User::where('email', 'treasurer@himatekom.org')->first();
        $chair = User::where('email', 'chair@himatekom.org')->first();
        
        // Create income transactions for organization
        for ($i = 1; $i <= 5; $i++) {
            $department = $departments->random();
            $date = now()->subDays(rand(10, 90));
            
            FinancialTransaction::create([
                'transaction_date' => $date,
                'amount' => rand(5, 20) * 1000000, // 5-20 million
                'type' => 'income',
                'category' => 'University Funding',
                'description' => 'Periodic funding from university for organizational activities',
                'department_id' => $department->id,
                'recorded_by' => $treasurer->id,
                'approved_by' => $chair->id,
                'approval_status' => 'approved',
            ]);
        }
        
        // Create income transactions for events (sponsorships, ticket sales)
        foreach ($events as $event) {
            if (rand(0, 1) == 1) { // 50% chance to have sponsorship
                FinancialTransaction::create([
                    'transaction_date' => $event->start_date->copy()->subDays(rand(10, 20)),
                    'amount' => rand(2, 10) * 1000000, // 2-10 million
                    'type' => 'income',
                    'category' => 'Sponsorship',
                    'description' => 'Sponsorship for ' . $event->title,
                    'event_id' => $event->id,
                    'department_id' => $event->department_id,
                    'recorded_by' => $treasurer->id,
                    'approved_by' => $chair->id,
                    'approval_status' => 'approved',
                ]);
            }
            
            if ($event->max_participants > 0 && rand(0, 1) == 1) { // 50% chance to have ticket sales
                FinancialTransaction::create([
                    'transaction_date' => $event->start_date->copy()->subDays(rand(1, 7)),
                    'amount' => rand(20, 50) * 1000 * rand(10, $event->max_participants),
                    'type' => 'income',
                    'category' => 'Ticket Sales',
                    'description' => 'Ticket sales for ' . $event->title,
                    'event_id' => $event->id,
                    'department_id' => $event->department_id,
                    'recorded_by' => $treasurer->id,
                    'approved_by' => $chair->id,
                    'approval_status' => 'approved',
                ]);
            }
        }
        
        // Create expense transactions for events
        foreach ($events as $event) {
            $categories = ['Venue Rental', 'Equipment', 'Catering', 'Promotional Materials', 'Speaker Honorarium', 'Transportation'];
            $numTransactions = rand(3, 6); // 3-6 expense categories per event
            
            for ($i = 0; $i < $numTransactions; $i++) {
                $category = $categories[array_rand($categories)];
                $transactionDate = $event->status === 'completed' 
                    ? $event->start_date->copy()->subDays(rand(1, 7)) 
                    : now()->subDays(rand(1, 10));
                
                $lpjId = null;
                if ($event->status === 'completed') {
                    $eventLpj = $lpjs->where('event_id', $event->id)->first();
                    if ($eventLpj) {
                        $lpjId = $eventLpj->id;
                    }
                }
                
                FinancialTransaction::create([
                    'transaction_date' => $transactionDate,
                    'amount' => rand(5, 30) * 100000, // 500k - 3 million
                    'type' => 'expense',
                    'category' => $category,
                    'description' => $category . ' expenses for ' . $event->title,
                    'event_id' => $event->id,
                    'lpj_id' => $lpjId,
                    'department_id' => $event->department_id,
                    'recorded_by' => $treasurer->id,
                    'approved_by' => $chair->id,
                    'approval_status' => 'approved',
                ]);
            }
        }
        
        // Create general organizational expenses
        $categories = ['Office Supplies', 'Equipment Maintenance', 'Software Subscriptions', 'Membership Activities', 'Training Materials'];
        
        for ($i = 1; $i <= 10; $i++) {
            $department = $departments->random();
            $category = $categories[array_rand($categories)];
            $date = now()->subDays(rand(1, 90));
            
            FinancialTransaction::create([
                'transaction_date' => $date,
                'amount' => rand(1, 10) * 100000, // 100k - 1 million
                'type' => 'expense',
                'category' => $category,
                'description' => 'Regular expenses for ' . $category,
                'department_id' => $department->id,
                'recorded_by' => $treasurer->id,
                'approved_by' => $chair->id,
                'approval_status' => 'approved',
            ]);
        }
    }
    
    private function createNews()
    {
        $departments = Department::all();
        $authors = User::where('role', 'staff')->orWhere('role', 'executive')->get();
        
        $newsItems = [
            [
                'title' => 'HIMATEKOM Successfully Hosts Annual Tech Exhibition',
                'excerpt' => 'The annual technology exhibition showcased innovative projects from computer engineering students.',
                'content' => "HIMATEKOM successfully hosted its Annual Technology Exhibition last weekend, showcasing innovative projects from computer engineering students. The event attracted over 300 visitors, including industry representatives, faculty members, and students from various departments.

The exhibition featured 25 student projects ranging from embedded systems and IoT devices to software applications and artificial intelligence solutions. The highlight of the event was the 'Smart Campus' project that demonstrated an integrated system for managing campus facilities using IoT sensors.

\"We are extremely pleased with the outcome of this year's exhibition. The quality of projects has significantly improved compared to previous years, showing the growing technical capabilities of our students,\" said the Chairperson of HIMATEKOM.

Several industry partners who attended the exhibition expressed interest in collaborating with students on further developing some of the showcased projects. This opens up potential internship and job opportunities for our students.

The exhibition concluded with an awards ceremony recognizing the most innovative and well-executed projects. Planning for next year's exhibition is already underway, with aims to make it even more engaging and impactful.",
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'New Board Members Elected for 2025-2026 Term',
                'excerpt' => 'HIMATEKOM welcomes the newly elected board members who will lead the organization for the 2025-2026 academic year.',
                'content' => "After a democratic election process, HIMATEKOM is pleased to announce the new board members who will lead the organization for the 2025-2026 academic year. The election saw active participation from the majority of members, reflecting the strong engagement within our community.

The elected board members are:

- Chairperson: Budi Santoso
- Vice Chairperson: Siti Rahayu
- Secretary: Ahmad Rizal
- Treasurer: Dewi Anggraini
- Head of Education and Development: Reza Pratama
- Head of Information and Communication: Putri Wulandari
- Head of Research and Technology: Andi Wijaya
- Head of Social and Community Service: Maya Indah
- Head of Events and Activities: Dimas Aditya

The outgoing board officially handed over their responsibilities in a ceremony attended by faculty advisors and organization members. The new leadership has already outlined their vision and priorities for the upcoming year, focusing on enhancing technical skills development, strengthening industry connections, and expanding community outreach programs.

\"We are honored to be entrusted with leading HIMATEKOM and will work diligently to build upon the strong foundation laid by our predecessors,\" said the newly elected Chairperson. \"Our goal is to create more opportunities for members to grow professionally while making meaningful contributions to both the academic and broader community.\"

The organization extends its gratitude to the outgoing board for their dedication and achievements during their tenure. A detailed transition process is underway to ensure continuity in the organization's operations and programs.",
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'HIMATEKOM Launches Mentoring Program for First-Year Students',
                'excerpt' => 'A new mentoring initiative pairs senior students with freshmen to help their academic and social integration.',
                'content' => "HIMATEKOM has launched a comprehensive mentoring program aimed at supporting first-year computer engineering students in their transition to university life. The program pairs experienced senior students with freshmen to provide academic guidance, social support, and insights into university resources.

The initiative, which began this semester, has already matched 45 mentors with 90 first-year students. Mentors undergo specific training to equip them with the necessary skills to effectively guide their mentees.

\"Starting university can be overwhelming, especially in a technically demanding field like computer engineering,\" explained the program coordinator. \"Our mentoring program creates a support system that helps new students navigate both academic challenges and campus life.\"

The program includes regular one-on-one meetings, study sessions, and social activities. Mentors assist with course selection, study strategies, and connecting mentees with additional resources such as tutoring services and academic advisors.

Feedback from participants has been overwhelmingly positive. First-year student Dina Permata shared, \"Having a mentor who has already gone through the same experiences has been invaluable. My mentor has helped me develop better study habits and introduced me to student clubs that align with my interests.\"

The organization plans to evaluate the program's effectiveness at the end of the academic year and make necessary adjustments to enhance its impact. If successful, the model may be expanded to other departments within the faculty.",
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Upcoming Workshop: Fundamentals of Data Science',
                'excerpt' => 'HIMATEKOM will host a two-day workshop covering the fundamentals of data science and machine learning.',
                'content' => "HIMATEKOM is excited to announce an upcoming workshop on the 'Fundamentals of Data Science' scheduled for next month. This two-day intensive training will cover essential concepts and practical applications in data science and machine learning.

The workshop will be conducted by Dr. Rina Wijaya, a renowned data scientist with experience in both academia and industry. Participants will learn about data collection, preprocessing, exploratory data analysis, statistical modeling, and introductory machine learning algorithms.

\"Data science has become a crucial field across various industries, and this workshop aims to provide students with a solid foundation to explore this exciting domain,\" said the workshop organizer. \"We've designed the content to be accessible to beginners while still offering valuable insights for those with some background knowledge.\"

The workshop will feature:
- Theoretical sessions explaining core concepts
- Hands-on exercises using Python and popular data science libraries
- Case studies demonstrating real-world applications
- Group projects to apply learned techniques

Registration is open to all students, with priority given to computer engineering students. The workshop has a limited capacity of 50 participants to ensure a quality learning experience. A nominal fee will be charged to cover materials and refreshments.

Interested students are encouraged to register early through our website. Basic programming knowledge is recommended but not required. Participants should bring their laptops with the necessary software installed (installation guidelines will be provided upon registration).",
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'HIMATEKOM Members Win Regional Programming Competition',
                'excerpt' => 'A team of three HIMATEKOM members secured first place in the regional programming competition.',
                'content' => "We are proud to announce that a team of three HIMATEKOM members has won first place in the Regional Programming Competition held last weekend. The team, consisting of Farhan Rizky, Indah Permata, and Bima Sakti, competed against 28 other teams from various universities across the region.

The competition tested participants' problem-solving skills, algorithmic thinking, and programming efficiency through a series of challenging problems. Our team demonstrated exceptional teamwork and technical prowess, solving 8 out of 10 problems within the allocated time.

\"We've been practicing together for months, solving problems daily and analyzing different algorithmic approaches,\" said team captain Farhan. \"The competition was intense, but our preparation and coordination really paid off.\"

The team's victory earns them an automatic qualification to the National Programming Competition scheduled for next semester. This achievement also brings recognition to our university's computer engineering program and highlights the technical capabilities of our students.

The Faculty Dean congratulated the team, stating: \"This achievement reflects the quality of our students and their dedication to excellence. We are committed to supporting such endeavors that allow students to apply their knowledge in competitive settings.\"

HIMATEKOM will organize a celebratory event to honor the winning team and share their competition experience with other members. The organization also plans to enhance its competitive programming training program to prepare more teams for future competitions.",
                'status' => 'published',
                'is_featured' => true,
            ],
        ];
        
        foreach ($newsItems as $index => $newsData) {
            $department = $departments->random();
            $author = $authors->random();
            $publishedDate = now()->subDays(rand(1, 30));
            
            News::create([
                'title' => $newsData['title'],
                'slug' => Str::slug($newsData['title']),
                'excerpt' => $newsData['excerpt'],
                'content' => $newsData['content'],
                'published_at' => $publishedDate,
                'status' => $newsData['status'],
                'is_featured' => $newsData['is_featured'],
                'department_id' => $department->id,
                'author_id' => $author->id,
                'views' => rand(50, 500),
            ]);
        }
    }
    
    private function createDocuments()
    {
        $departments = Department::all();
        $uploaders = User::where('role', 'staff')->orWhere('role', 'executive')->get();
        $approvers = User::where('signature_authority', true)->get();
        
        $categories = ['report', 'proposal', 'minutes', 'regulation', 'certificate', 'other'];
        $statuses = ['draft', 'pending', 'approved', 'published'];
        $visibilities = ['public', 'members', 'executives', 'admin'];
        
        for ($i = 1; $i <= 15; $i++) {
            $department = $departments->random();
            $uploader = $uploaders->random();
            $approver = $approvers->random();
            $category = $categories[array_rand($categories)];
            $status = $statuses[array_rand($statuses)];
            $visibility = $visibilities[array_rand($visibilities)];
            
            $title = '';
            switch ($category) {
                case 'report':
                    $title = 'Monthly Activity Report - ' . now()->subMonths(rand(1, 6))->format('F Y');
                    break;
                case 'proposal':
                    $title = 'Event Proposal: ' . ['Tech Workshop', 'Networking Event', 'Community Service', 'Skills Training', 'Competition'][rand(0, 4)];
                    break;
                case 'minutes':
                    $title = 'Meeting Minutes - ' . now()->subDays(rand(7, 60))->format('d F Y');
                    break;
                case 'regulation':
                    $title = 'Guidelines for ' . ['Event Organization', 'Financial Procedures', 'Member Recruitment', 'Academic Support', 'External Relations'][rand(0, 4)];
                    break;
                case 'certificate':
                    $title = 'Certificate Template for ' . ['Event Participation', 'Workshop Completion', 'Achievement Recognition', 'Volunteering', 'Organization Membership'][rand(0, 4)];
                    break;
                default:
                    $title = 'Document ' . $i . ': General Information';
            }
            
            Document::create([
                'title' => $title,
                'description' => 'Sample document for testing purposes: ' . $title,
                'file_path' => 'public/documents/sample_' . $i . '.pdf',
                'file_type' => 'application/pdf',
                'file_size' => rand(100, 5000),
                'category' => $category,
                'visibility' => $visibility,
                'status' => $status,
                'version' => rand(1, 3),
                'department_id' => $department->id,
                'uploaded_by' => $uploader->id,
                'approved_by' => $status === 'approved' || $status === 'published' ? $approver->id : null,
                'approval_date' => ($status === 'approved' || $status === 'published') ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
    
    private function createGalleries()
    {
        $events = Event::where('status', 'completed')->get();
        $creators = User::where('role', 'staff')->orWhere('role', 'executive')->get();
        
        foreach ($events as $event) {
            $creator = $creators->random();
            
            $gallery = Gallery::create([
                'title' => 'Gallery: ' . $event->title,
                'description' => 'Photo documentation of ' . $event->title,
                'event_id' => $event->id,
                'is_featured' => rand(0, 1) === 1,
                'status' => 'published',
                'created_by' => $creator->id,
            ]);
            
            // Create some gallery images
            $imageCount = rand(5, 15);
            for ($i = 1; $i <= $imageCount; $i++) {
                GalleryImages::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => 'public/galleries/event_' . $event->id . '_image_' . $i . '.jpg',
                    'caption' => 'Image ' . $i . ' from ' . $event->title,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}