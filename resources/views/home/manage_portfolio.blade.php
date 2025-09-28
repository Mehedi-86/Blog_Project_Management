<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <style>
        .portfolio-container { max-width: 900px; margin: auto; font-family: Arial, sans-serif; }
        .portfolio-title { text-align: center; font-size: 2.5rem; margin-bottom: 40px; }
        .section { background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); padding: 25px; margin-bottom: 30px; }
        .section-title { font-size: 1.8rem; font-weight: 600; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; }
        .item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .item:last-child { border-bottom: none; }
        /* --- FONT SIZE INCREASED --- */
        .item-title { font-weight: bold; font-size: 1.2rem; }
        .item-subtitle { color: #555; font-size: 1.1rem; }
        /* --- FONT SIZE DECREASED --- */
        .form-title { font-size: 1rem; font-weight: bold; margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background: #0056b3; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header_section">
        @include('home.header')
    </div>

    <div class="portfolio-container">
        <h1 class="portfolio-title">Manage Your Portfolio</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <!-- Work Experience Section -->
        <div class="section">
            <h2 class="section-title">💼 Work Experience</h2>
            @forelse($workExperiences as $work)
                <div class="item">
                    <div class="item-title">{{ $work->workplace_name }}</div>
                    <div class="item-subtitle">{{ $work->designation }} ({{ $work->year }})</div>
                </div>
            @empty
                <p>You have not added any work experience yet.</p>
            @endforelse

            <hr>
            <h3 class="form-title">Add New Work Experience</h3>
            <form action="{{ route('portfolio.add.work') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="workplace_name">Workplace Name</label>
                    <input type="text" name="workplace_name" required>
                </div>
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" name="designation" required>
                </div>
                <div class="form-group">
                    <label for="year">Year(s)</label>
                    <input type="text" name="year" placeholder="e.g., 2020 - 2023" required>
                </div>
                <button type="submit" class="btn-submit">Add Experience</button>
            </form>
        </div>

        <!-- Education Section -->
        <div class="section">
            <h2 class="section-title">🎓 Education</h2>
            @forelse($educations as $edu)
                <div class="item">
                    <div class="item-title">{{ $edu->school_name }}</div>
                    <div class="item-subtitle">{{ $edu->degree }} (Graduated {{ $edu->graduation_year }})</div>
                </div>
            @empty
                <p>You have not added any education history yet.</p>
            @endforelse
            
            <hr>
            <h3 class="form-title">Add New Education</h3>
            <form action="{{ route('portfolio.add.education') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="school_name">School/University Name</label>
                    <input type="text" name="school_name" required>
                </div>
                <div class="form-group">
                    <label for="degree">Degree</label>
                    <input type="text" name="degree" placeholder="e.g., Bachelor of Science" required>
                </div>
                <div class="form-group">
                    <label for="graduation_year">Graduation Year</label>
                    <input type="text" name="graduation_year" required>
                </div>
                <button type="submit" class="btn-submit">Add Education</button>
            </form>
        </div>

        <!-- Activities Section -->
        <div class="section">
            <h2 class="section-title">🏆 Extra-Curricular Activities</h2>
            @forelse($activities as $activity)
                <div class="item">
                    <div class="item-title">{{ $activity->name }}</div>
                    <div class="item-subtitle">{{ $activity->time_duration }}</div>
                    <p>{{ $activity->description }}</p>
                    @if($activity->github_link)
                        <a href="{{ $activity->github_link }}" target="_blank">View on GitHub</a>
                    @endif
                </div>
            @empty
                <p>You have not added any activities yet.</p>
            @endforelse

            <hr>
            <h3 class="form-title">Add New Activity</h3>
            <form action="{{ route('portfolio.add.activity') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Activity/Project Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="time_duration">Time Duration</label>
                    <input type="text" name="time_duration" placeholder="e.g., 3 Months" required>
                </div>
                 <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="github_link">GitHub Link (Optional)</label>
                    <input type="url" name="github_link">
                </div>
                <button type="submit" class="btn-submit">Add Activity</button>
            </form>
        </div>
    </div>

    @include('home.footer')
</body>
</html>

