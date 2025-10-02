<!DOCTYPE html>
<html lang="en">
<head>
   
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .schema-container-wrapper {
            background-color: #f4f7f6;
            padding: 40px 15px;
        }
        .schema-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 40px;
        }
        .schema-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            max-width: 1400px;
            margin: auto;
        }
        .schema-table {
            width: 300px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
        }
        .schema-table-header {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            border-radius: 8px 8px 0 0;
        }
        .schema-table-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #343a40;
        }
        .schema-columns {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .schema-column {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            font-size: 0.9rem;
            border-top: 1px solid #f1f1f1;
        }
        .schema-column:first-child {
            border-top: none;
        }
        .column-name {
            font-weight: 500;
            color: #333;
        }
        .column-meta {
            text-align: right;
        }
        .column-type {
            color: #6c757d;
        }
        .key-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 8px;
        }
        .pk-badge { background-color: #ffc107; color: #333; }
        .fk-badge { background-color: #007bff; color: #fff; }

        /* --- Styles for PDF Schema Diagram Section --- */
        .schema-pdf-wrapper {
            background-color: #ffffff;
            padding: 40px 15px;
            text-align: center;
        }
        .schema-pdf-container iframe {
            width: 100%;
            max-width: 1200px;
            height: 800px;
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }
       
    </style>
</head>
<body>

    <div class="schema-container-wrapper">
        <h1 class="schema-title">Database Schema Tables</h1>
        <div class="schema-container">
            <!-- Users Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>users</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">name</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">email</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">role</span><span class="column-meta"><span class="column-type">enum</span></span></li>
                    <li class="schema-column"><span class="column-name">is_banned</span><span class="column-meta"><span class="column-type">boolean</span></span></li>
                </ul>
            </div>
            <!-- Posts Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>posts</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">title</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">views</span><span class="column-meta"><span class="column-type">int</span></span></li>
                    <li class="schema-column"><span class="column-name">category_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">status</span><span class="column-meta"><span class="column-type">enum</span></span></li>
                </ul>
            </div>
            <!-- Categories Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>categories</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">name</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                </ul>
            </div>
            <!-- Comments Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>comments</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">post_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">content</span><span class="column-meta"><span class="column-type">text</span></span></li>
                </ul>
            </div>
            <!-- Likes Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>likes</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">post_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                </ul>
            </div>
            <!-- Post User Saves Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>post_user_saves</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">post_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                </ul>
            </div>
             <!-- Follows Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>follows</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">follower_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">following_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                </ul>
            </div>
            <!-- Notifications Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>notifications</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">type</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">data</span><span class="column-meta"><span class="column-type">json</span></span></li>
                </ul>
            </div>
            <!-- Reports Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>reports</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">post_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">reported_by</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">reason</span><span class="column-meta"><span class="column-type">text</span></span></li>
                </ul>
            </div>
            <!-- Work Experiences Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>work_experiences</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">workplace_name</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">designation</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                </ul>
            </div>
            <!-- Educations Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>educations</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">school_name</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">degree</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                </ul>
            </div>
            <!-- Extra Curricular Activities Table -->
            <div class="schema-table">
                <div class="schema-table-header"><h3><i class="fas fa-table me-2"></i>extra_curricular_activities</h3></div>
                <ul class="schema-columns">
                    <li class="schema-column"><span class="column-name">id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge pk-badge">PK</span></span></li>
                    <li class="schema-column"><span class="column-name">user_id</span><span class="column-meta"><span class="column-type">bigint</span><span class="key-badge fk-badge">FK</span></span></li>
                    <li class="schema-column"><span class="column-name">name</span><span class="column-meta"><span class="column-type">varchar</span></span></li>
                    <li class="schema-column"><span class="column-name">description</span><span class="column-meta"><span class="column-type">text</span></span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- PDF Schema Diagram Section -->
    <div class="schema-pdf-wrapper">
        <h1 class="schema-title">Database Schema PDF</h1>
        <div class="schema-pdf-container">
            <iframe src="{{ asset('downloads/db_schema_diagram.pdf') }}"></iframe>
        </div>
    </div>

</body>
</html>