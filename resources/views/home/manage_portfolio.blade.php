<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .portfolio-container { max-width: 900px; margin: auto; font-family: Arial, sans-serif; }
        .portfolio-title { text-align: center; font-size: 2.5rem; margin-bottom: 40px; color: #333; }
        .section { background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); padding: 25px; margin-bottom: 30px; }
        .section-title { font-size: 1.8rem; font-weight: 600; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; }
        .item { display: flex; justify-content: space-between; align-items: center; padding: 15px 5px; border-bottom: 1px solid #eee; }
        .item:last-of-type { border-bottom: none; }
        .item-content .item-title { font-weight: bold; font-size: 1.1rem; }
        .item-content .item-subtitle { color: #555; font-size: 0.95rem; }
        .item-actions { display: flex; gap: 15px; }
        .action-btn { background: none; border: none; cursor: pointer; font-size: 1rem; color: #6c757d; transition: color 0.2s; }
        .action-btn:hover { color: #343a40; }
        .delete-btn { color: #dc3545; }
        .delete-btn:hover { color: #a71d2a; }
        
        /* Add & Modal Form Styles */
        .add-btn { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: 500; transition: background-color 0.2s; }
        .add-btn:hover { background-color: #0056b3; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center; }
        .modal-content { background-color: #fefefe; margin: auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 500px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { padding: 10px 15px; border-radius: 5px; border: none; cursor: pointer; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-primary { background-color: #007bff; color: white; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; }

        /* Styles for activity description and link */
        .activity-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 8px;
            margin-bottom: 8px;
        }
        .github-link {
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .github-link:hover {
            text-decoration: underline;
        }
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
                    <div class="item-content">
                        <div class="item-title">{{ $work->workplace_name }}</div>
                        <div class="item-subtitle">{{ $work->designation }} ({{ $work->year }})</div>
                    </div>
                    <div class="item-actions">
                        <button onclick="openEditWorkModal({{ json_encode($work) }})" class="action-btn" title="Edit"><i class="fas fa-pen"></i></button>
                        <form action="{{ route('portfolio.delete.work', $work->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p>You have not added any work experience yet.</p>
            @endforelse
            <button class="add-btn mt-3" onclick="toggleForm('workForm')">Add Work Experience</button>
            <div id="workForm" style="display: none; margin-top: 15px;">
                <form action="{{ route('portfolio.add.work') }}" method="POST">
                    @csrf
                    <div class="form-group"><label>Workplace Name</label><input type="text" name="workplace_name" class="form-control" required></div>
                    <div class="form-group"><label>Designation</label><input type="text" name="designation" class="form-control" required></div>
                    <div class="form-group"><label>Year(s)</label><input type="text" name="year" class="form-control" placeholder="e.g., 2020 - 2023" required></div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>

        <!-- Education Section -->
        <div class="section">
            <h2 class="section-title">🎓 Education</h2>
            @forelse($educations as $education)
                <div class="item">
                    <div class="item-content">
                        <div class="item-title">{{ $education->school_name }}</div>
                        <div class="item-subtitle">{{ $education->degree }} (Graduated {{ $education->graduation_year }})</div>
                    </div>
                    <div class="item-actions">
                        <button onclick="openEditEducationModal({{ json_encode($education) }})" class="action-btn" title="Edit"><i class="fas fa-pen"></i></button>
                        <form action="{{ route('portfolio.delete.education', $education->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p>You have not added any education history yet.</p>
            @endforelse
            <button class="add-btn mt-3" onclick="toggleForm('educationForm')">Add Education</button>
            <div id="educationForm" style="display: none; margin-top: 15px;">
                <form action="{{ route('portfolio.add.education') }}" method="POST">
                    @csrf
                    <div class="form-group"><label>School/University Name</label><input type="text" name="school_name" class="form-control" required></div>
                    <div class="form-group"><label>Degree</label><input type="text" name="degree" class="form-control" placeholder="e.g., Bachelor of Science" required></div>
                    <div class="form-group"><label>Graduation Year</label><input type="text" name="graduation_year" class="form-control" required></div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>

        <!-- Activities Section -->
        <div class="section">
            <h2 class="section-title">🏆 Extra-Curricular Activities</h2>
            @forelse($activities as $activity)
                <div class="item">
                    <div class="item-content">
                        <div class="item-title">{{ $activity->name }}</div>
                        <div class="item-subtitle">{{ $activity->time_duration }}</div>
                        {{-- UPDATED: Description and GitHub link are now visible --}}
                        <p class="activity-description">{{ $activity->description }}</p>
                        @if($activity->github_link)
                            <a href="{{ $activity->github_link }}" class="github-link" target="_blank">
                                <i class="fab fa-github"></i> GitHub Link
                            </a>
                        @endif
                    </div>
                    <div class="item-actions">
                        <button onclick="openEditActivityModal({{ json_encode($activity) }})" class="action-btn" title="Edit"><i class="fas fa-pen"></i></button>
                        <form action="{{ route('portfolio.delete.activity', $activity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            @empty
                <p>You have not added any activities yet.</p>
            @endforelse
            <button class="add-btn mt-3" onclick="toggleForm('activityForm')">Add Activity</button>
            <div id="activityForm" style="display: none; margin-top: 15px;">
                <form action="{{ route('portfolio.add.activity') }}" method="POST">
                    @csrf
                    <div class="form-group"><label>Activity/Project Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label>Time Duration</label><input type="text" name="time_duration" class="form-control" placeholder="e.g., 3 Months" required></div>
                    <div class="form-group"><label>Description</label><textarea name="description" class="form-control"></textarea></div>
                    <div class="form-group"><label>GitHub Link (Optional)</label><input type="url" name="github_link" class="form-control"></div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Work Modal -->
    <div id="editWorkModal" class="modal"><div class="modal-content"><h3 class="fw-bold mb-4">Edit Work Experience</h3><form method="POST" id="editWorkForm">@csrf @method('PUT')<div class="form-group"><label>Workplace Name</label><input type="text" name="workplace_name" id="editWorkplaceName" class="form-control" required></div><div class="form-group"><label>Designation</label><input type="text" name="designation" id="editDesignation" class="form-control" required></div><div class="form-group"><label>Year(s)</label><input type="text" name="year" id="editYear" class="form-control" required></div><div class="d-flex justify-content-end gap-2 mt-3"><button type="button" onclick="closeModal('editWorkModal')" class="btn btn-secondary">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form></div></div>
    
    <!-- Edit Education Modal -->
    <div id="editEducationModal" class="modal"><div class="modal-content"><h3 class="fw-bold mb-4">Edit Education</h3><form method="POST" id="editEducationForm">@csrf @method('PUT')<div class="form-group"><label>School/University Name</label><input type="text" name="school_name" id="editSchoolName" class="form-control" required></div><div class="form-group"><label>Degree</label><input type="text" name="degree" id="editDegree" class="form-control" required></div><div class="form-group"><label>Graduation Year</label><input type="text" name="graduation_year" id="editGraduationYear" class="form-control" required></div><div class="d-flex justify-content-end gap-2 mt-3"><button type="button" onclick="closeModal('editEducationModal')" class="btn btn-secondary">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form></div></div>

    <!-- Edit Activity Modal -->
    <div id="editActivityModal" class="modal"><div class="modal-content"><h3 class="fw-bold mb-4">Edit Activity</h3><form method="POST" id="editActivityForm">@csrf @method('PUT')<div class="form-group"><label>Activity/Project Name</label><input type="text" name="name" id="editActivityName" class="form-control" required></div><div class="form-group"><label>Time Duration</label><input type="text" name="time_duration" id="editTimeDuration" class="form-control" required></div><div class="form-group"><label>Description</label><textarea name="description" id="editDescription" class="form-control"></textarea></div><div class="form-group"><label>GitHub Link</label><input type="url" name="github_link" id="editGithubLink" class="form-control"></div><div class="d-flex justify-content-end gap-2 mt-3"><button type="button" onclick="closeModal('editActivityModal')" class="btn btn-secondary">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form></div></div>

    @include('home.footer')

    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditWorkModal(work) {
            document.getElementById('editWorkplaceName').value = work.workplace_name;
            document.getElementById('editDesignation').value = work.designation;
            document.getElementById('editYear').value = work.year;
            document.getElementById('editWorkForm').action = `/portfolio/work/${work.id}`;
            document.getElementById('editWorkModal').style.display = 'flex';
        }

        function openEditEducationModal(education) {
            document.getElementById('editSchoolName').value = education.school_name;
            document.getElementById('editDegree').value = education.degree;
            document.getElementById('editGraduationYear').value = education.graduation_year;
            document.getElementById('editEducationForm').action = `/portfolio/education/${education.id}`;
            document.getElementById('editEducationModal').style.display = 'flex';
        }

        function openEditActivityModal(activity) {
            document.getElementById('editActivityName').value = activity.name;
            document.getElementById('editTimeDuration').value = activity.time_duration;
            document.getElementById('editDescription').value = activity.description;
            document.getElementById('editGithubLink').value = activity.github_link;
            document.getElementById('editActivityForm').action = `/portfolio/activity/${activity.id}`;
            document.getElementById('editActivityModal').style.display = 'flex';
        }

        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>

