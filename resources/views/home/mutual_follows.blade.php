<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      @include('home.homecss')

      <style>
         .connections-container {
            padding: 50px 15px;
            background-color: #f8f9fa; /* A light, neutral background */
            font-family: 'Poppins', sans-serif;
            min-height: 70vh; /* Ensures the container takes up a good amount of space */
         }
         .connections-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: 700;
            color: #34495e; /* A professional dark blue */
         }
         .connections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Responsive grid */
            gap: 25px; /* Space between cards */
            max-width: 1200px;
            margin: 0 auto;
         }
         .connection-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 25px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
         }
         .connection-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
         }
         .avatar-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e9ecef;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
         }
         .avatar-placeholder svg {
            width: 40px;
            height: 40px;
         }
         .connection-card h3 {
            margin: 0 0 5px 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
         }
         .connection-card p {
            margin: 0;
            font-size: 0.9rem;
            color: #7f8c8d; /* A softer gray for email */
         }

         .no-connections-message {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 0 auto;
         }
         .no-connections-message h3 {
            font-size: 1.5rem;
            color: #34495e;
            margin-bottom: 15px;
         }
         .no-connections-message p {
            color: #7f8c8d;
            line-height: 1.6;
         }
         .no-connections-message .btn-explore {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
         }
         .no-connections-message .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
         }
      </style>
   </head>
   <body>
      <!-- header section start -->
      <div class="header_section">
        @include('home.header')
      </div>
      <!-- header section end -->
      
      <div class="connections-container">
         <h1 class="connections-title">My Connections</h1>
         <p class="text-center text-muted mb-5">These are the people you follow who also follow you back.</p>

         @forelse ($connections as $user)
            @if ($loop->first)
               <div class="connections-grid">
            @endif

            <div class="connection-card">
               <div class="avatar-placeholder">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
               </div>
               <h3>{{ $user->name }}</h3>
               <p>{{ $user->email }}</p>
            </div>

            @if ($loop->last)
               </div>
            @endif
         @empty
            <div class="no-connections-message">
               <h3>No Mutual Connections Found</h3>
               <p>
                  A mutual connection is someone you follow who also follows you back.
                  Start following more users to build your network!
               </p>
               <a href="{{ route('followerPage') }}" class="btn-explore">Find People to Follow</a>
            </div>
         @endforelse
      </div>
      
      @include('home.footer')
   </body>
</html>
