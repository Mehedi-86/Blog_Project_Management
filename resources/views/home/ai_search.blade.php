<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    
    {{-- Using a modern, readable font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .ai-search-container {
            width: 90%;
            max-width: 900px;
            margin: auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        }
        .ai-search-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .ai-search-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #222;
        }
        .ai-search-header p {
            font-size: 16px;
            color: #666;
            margin-top: 8px;
        }
        .search-bar-wrapper {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        #ai-search-input {
            flex-grow: 1;
            padding: 14px 18px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #ai-search-input:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.2);
        }
        #ai-search-button {
            padding: 0 25px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(79, 172, 254, 0.4);
        }
        #ai-search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 172, 254, 0.5);
        }
        #ai-search-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .loader {
            display: none; /* Hidden by default */
            text-align: center;
            padding: 30px;
            font-size: 16px;
            color: #555;
        }
        
        /* Post card styling (re-using from previous example) */
        .post-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .post-card {
            background-color: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .post-card-content {
            padding: 25px;
        }
        .post-card-category {
            display: inline-block;
            padding: 5px 12px;
            background-color: #e0f2fe;
            color: #0284c7;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .post-card-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 10px;
            /* Added to prevent long titles from breaking layout */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limit to 2 lines */
            -webkit-box-orient: vertical;
        }
        .post-card-meta {
            font-size: 14px;
            color: #718096;
            margin-bottom: 15px;
        }
        .post-card-footer {
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #4a5568;
        }
        .post-card-link {
            text-decoration: none;
            color: #4facfe;
            font-weight: 600;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="header_section">
        @include('home.header')
    </div>

    <!-- AI Search Content -->
    <div class="ai-search-container">
        <div class="ai-search-header">
            <h1>AI Powered Post Search</h1>
            <p>Ask a question or describe what you're looking for, and our AI will find related posts.</p>
        </div>

        <div class="search-bar-wrapper">
            <input type="text" id="ai-search-input" placeholder="e.g., 'a post about traveling' or 'what's new in tech?'">
            <button id="ai-search-button">Search</button>
        </div>

        <div class="loader" id="loader">
            <p>Asking AI for relevant categories...</p>
        </div>

        <div class="post-card-grid" id="results-container">
            <!-- AI search results will be injected here -->
        </div>
    </div>

    <!-- Footer -->
    @include('home.footer')

    <script>
        // --- This is how we pass data from PHP (controller) to JavaScript ---
        // $categoryNames is passed from showAiSearchPage() in HomeController
        const ALL_CATEGORIES = @json($categoryNames);

        // --- Element References ---
        const searchInput = document.getElementById('ai-search-input');
        const searchButton = document.getElementById('ai-search-button');
        const loader = document.getElementById('loader');
        const resultsContainer = document.getElementById('results-container');

        // --- Gemini API Configuration ---
        const apiKey = "AIzaSyD1jyfkkpuF1UIOSxt3VyY8zZnaKXuXxs0"; // Leave as-is, Canvas will provide it
        const geminiApiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;

        // --- Backend URL ---
        // We use the route() helper to get the correct URL
        const backendSearchUrl = '{{ route('ai.search.handler') }}';

        // --- Event Listener ---
        searchButton.addEventListener('click', handleSearch);
        searchInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                handleSearch();
            }
        });

        async function handleSearch() {
            const query = searchInput.value.trim();
            if (!query) return; // Do nothing if input is empty

            // 1. Set UI to loading state
            setLoading(true, "Asking AI for relevant categories...");
            resultsContainer.innerHTML = ''; // Clear old results

            try {
                // 2. Step 1: Call Gemini AI to get relevant categories
                const categories = await getCategoriesFromAI(query);

                if (!categories || categories.length === 0) {
                    showNoResults("AI couldn't find any matching categories for that query.");
                    setLoading(false, ""); // Stop loading
                    return;
                }
                
                // Update loader text
                setLoading(true, `Found categories: ${categories.join(', ')}. Fetching posts...`);

                // 3. Step 2: Call our own backend to get posts from those categories
                const posts = await getPostsFromBackend(categories);

                if (!posts || posts.length === 0) {
                    showNoResults("Found matching categories, but no posts in them yet.");
                    setLoading(false, ""); // Stop loading
                    return;
                }

                // 4. Step 3: Render the posts on the page
                renderPosts(posts);

            } catch (error) {
                // --- THIS IS THE IMPORTANT CHANGE ---
                // We now show the *specific* error message.
                console.error('Search failed:', error);
                showNoResults(`An error occurred: ${error.message}. Check the console (F12) for more details.`);
                // --- END OF CHANGE ---

            } finally {
                // 5. Unset loading state
                setLoading(false, "");
            }
        }

        /**
         * Step 1: Calls the Gemini API to get a list of category names.
         */
        async function getCategoriesFromAI(query) {
            // This prompt is critical. It forces the AI to only respond with
            // category names that actually exist in our database.
            const systemPrompt = `You are a search assistant for a blog. The available post categories are: ${JSON.stringify(ALL_CATEGORIES)}.
Analyze the user's query: "${query}".
Respond ONLY with a JSON array of the exact category names from the list that are relevant to the query.
If no categories seem to match, return an empty array. Do not add any other text or explanations.`;
            
            const payload = {
                contents: [{ parts: [{ text: query }] }],
                systemInstruction: {
                    parts: [{ text: systemPrompt }]
                },
                // We force the AI to respond in the exact JSON format we want
                generationConfig: {
                    responseMimeType: "application/json",
                    responseSchema: {
                        type: "ARRAY",
                        items: { "type": "STRING" }
                    }
                }
            };

            // This function will handle the fetch and retries
            const data = await fetchWithBackoff(geminiApiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            // The AI returns the JSON as text, so we parse it
            const jsonText = data.candidates[0].content.parts[0].text;
            return JSON.parse(jsonText);
        }

        /**
         * Step 2: Calls our Laravel backend to get posts.
         */
        async function getPostsFromBackend(categories) {
            const response = await fetch(backendSearchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Important for Laravel security
                },
                body: JSON.stringify({ categories: categories })
            });
            if (!response.ok) {
                // This will now throw a specific error message
                throw new Error(`Backend request failed with status ${response.status} (${response.statusText})`);
            }
            return await response.json();
        }

        /**
         * Step 3: Renders the post cards to the DOM.
         */
        function renderPosts(posts) {
            let html = '';
            posts.forEach(post => {
                // Format the date nicely
                const postDate = new Date(post.created_at).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric'
                });
                
                // --- THIS IS THE SECOND CHANGE ---
                // Fixed the URL to use your 'posts.details' route.
                // This route doesn't take an ID, so all links will go to the main post details table.
                // This is a limitation of your current routes, but it fixes the broken link.
                const postUrl = `{{ route('posts.details') }}`; 

                html += `
                    <div class="post-card">
                        <div class="post-card-content">
                            <span class="post-card-category">${post.category_name || 'General'}</span>
                            <h3 class="post-card-title">${post.title}</h3>
                            <p class="post-card-meta">By <strong>${post.author_name}</strong></p>
                            <div class="post-card-footer">
                                <span>${postDate}</span>
                                <a href="${postUrl}" class="post-card-link">Read More &rarr;</a>
                            </div>
                        </div>
                    </div>
                `;
            });
            resultsContainer.innerHTML = html;
        }

        // --- Helper Functions ---

        function setLoading(isLoading, message) {
            if (isLoading) {
                loader.innerHTML = `<p>${message}</p>`;
                loader.style.display = 'block';
                searchButton.disabled = true;
                searchButton.textContent = 'Searching...';
            } else {
                loader.style.display = 'none';
                searchButton.disabled = false;
                searchButton.textContent = 'Search';
            }
        }

        function showNoResults(message) {
            resultsContainer.innerHTML = `<p style="text-align: center; color: #666; padding: 20px;">${message}</p>`;
        }

        // Utility function for retrying fetch calls
        async function fetchWithBackoff(url, options, retries = 3, delay = 1000) {
            try {
                const response = await fetch(url, options);
                if (!response.ok) {
                    // 429 = Too Many Requests (rate limiting)
                    if (response.status === 429 && retries > 0) {
                        // Don't log to console, just wait and retry
                        await new Promise(resolve => setTimeout(resolve, delay));
                        return fetchWithBackoff(url, options, retries - 1, delay * 2); // Exponential backoff
                    }
                    // For other errors, throw immediately to be caught by handleSearch
                    throw new Error(`Gemini API request failed with status ${response.status}`);
                }
                return await response.json();
            } catch (error) {
                if (retries > 0) {
                    // Retry on network errors
                    await new Promise(resolve => setTimeout(resolve, delay));
                    return fetchWithBackoff(url, options, retries - 1, delay * 2);
                }
                // After all retries fail, throw the error
                console.error("Failed to fetch after multiple retries:", error);
                throw error;
            }
        }

    </script>

</body>
</html>

