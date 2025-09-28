<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      @include('home.homecss')

      <!-- Internal CSS for styling the page and fixing layout issues -->
      <style>
         /* This new class wraps the main page content to provide consistent spacing */
         .main_content_area {
            padding: 40px 15px; /* Adds vertical and horizontal padding */
            width: 100%;
            background-color: #f8f9fa; /* A light background to distinguish from header/footer */
         }

         .edit-post-container {
            max-width: 700px;
            /* The top and bottom margin is now handled by .main_content_area */
            margin: 0 auto; 
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
         }

         .edit-post-container h3 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px; /* Increased margin for better heading separation */
         }

         .form-group {
            margin-bottom: 20px;
         }

         .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
         }

         .form-group input,
         .form-group textarea {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            color: #555;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
         }

         .form-group input:focus,
         .form-group textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2); /* Adds a nice focus glow */
         }

         .form-group textarea {
            min-height: 120px;
            resize: vertical;
         }

         .btn-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
         }

         .btn-submit:hover {
            background: linear-gradient(90deg, #0056b3, #003d80);
            transform: translateY(-2px); /* Slight lift on hover */
         }
      </style>
   </head>
   <body>
      <!-- header section start -->
      <div class="header_section">
         @include('home.header')
      </div>
      <!-- header section end -->

      <!-- Main Content Section -->
      <div class="main_content_area">
         <div class="edit-post-container">
            <h3>✏️ Edit Your Post</h3>
            <form action="{{ route('updatePost', $post->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ $post->title }}" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" required>{{ $post->content }}</textarea>
                </div>
                <button type="submit" class="btn-submit">💾 Update Post</button>
            </form>
         </div>
      </div>
      <!-- Main Content Section End -->

      <!-- footer section start -->
      @include('home.footer')
      <!-- footer section end -->
   </body>
</html>
