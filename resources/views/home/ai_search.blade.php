<!DOCTYPE html>
<html lang="en">
<head>
    @include('home.homecss')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- STYLES ARE UNCHANGED --- */
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
        
        /* Post card styling */
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
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
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

    {{-- Safely encode PHP variable for the header AND for the AI --}}
    @php
        $categoryNamesJson = json_encode($categoryNames ?? []);
    @endphp

</head>
<body>
    <!-- Header -->
    <div class="header_section">
        @include('home.header')
    </div>

    <!-- AI Search Content -->
    <div class="ai-search-container">
        <div class="ai-search-header">
            <h1>Smart Search</h1>
            <p>Ask a question or describe what you're looking for, and our AI will find related posts.</p>
        </div>

        <div class="search-bar-wrapper">
            <input type="text" id="ai-search-input" placeholder="e.g., 'a post about a hidden journey'">
            <button id="ai-search-button">Search</button>
        </div>

        <div class="loader" id="loader">
            <p>Asking AI to analyze query...</p>
        </div>

        <div class="post-card-grid" id="results-container">
            <!-- AI search results will be injected here -->
        </div>
    </div>

    <!-- Footer -->
    @include('home.footer')

    <script>
        // --- This variable is now used by the AI prompt ---
        const ALL_CATEGORIES_JSON = {!! $categoryNamesJson !!};

        // --- Element References ---
        const searchInput = document.getElementById('ai-search-input');
        const searchButton = document.getElementById('ai-search-button');
        const loader = document.getElementById('loader');
        const resultsContainer = document.getElementById('results-container');

        // --- Gemini API Configuration ---
        const apiKey = "AIzaSyD1jyfkkpuF1UIOSxt3VyY8zZnaKXuXxs0"; 
        const geminiApiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;

        // --- Backend URL ---
        const backendSearchUrl = '{{ route('ai.search.handler') }}';
        const postDetailBaseUrl = '{{ url('/post') }}'; // Correct base URL for posts

        // --- Event Listener ---
        searchButton.addEventListener('click', handleSearch);
        searchInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                handleSearch();
            }
        });

        async function handleSearch() {
            const query = searchInput.value.trim();
            if (!query) return; 

            setLoading(true, "Asking AI to analyze your query...");
            resultsContainer.innerHTML = ''; 

            try {
                // 2. Step 1: Call Gemini AI to get both keywords AND categories
                const analysis = await getAiAnalysis(query);

                if (!analysis || (analysis.keywords.length === 0 && analysis.categories.length === 0)) {
                    showNoResults("AI couldn't find any specific keywords or categories in that query.");
                    setLoading(false, "");
                    return;
                }
                
                let loaderMsg = `Searching by keywords, then falling back to categories...`;
                setLoading(true, loaderMsg);

                // 3. Step 2: Call our own backend with BOTH lists
                const posts = await getPostsFromBackend(analysis.keywords, analysis.categories);

                if (!posts || posts.length === 0) {
                    showNoResults("AI analyzed your query, but no posts matched.");
                    setLoading(false, "");
                    return;
                }

                // 4. Step 3: Render the posts
                renderPosts(posts);

            } catch (error) {
                console.error('Search failed:', error);
                showNoResults(`An error occurred: ${error.message}. Check the console (F12) for more details.`);
            } finally {
                setLoading(false, "");
            }
        }

        /**
         * Step 1: Calls the Gemini API to get both keywords and categories.
         */
        async function getAiAnalysis(query) {
            
            // --- This prompt asks for BOTH keywords and categories ---
            const systemPrompt = `You are a search assistant for a blog.
The user's query is: "${query}".
The available post categories are: ${JSON.stringify(ALL_CATEGORIES_JSON)}.

Your job is two-fold:
1.  **Keywords**: Understand the user's *intent* and provide specific, meaningful keywords (nouns, proper nouns, or thematic adjectives) that describe the *main topic*.
    -   If the user searches for "journey", they almost always mean "travel". Return "travel" instead of "journey".
    -   IGNORE common filler words like "post", "article", "related", "find", "me", "a", "about".
2.  **Categories**: Identify any categories from the *exact* list above that are relevant to the query.

Respond ONLY with a JSON object in this exact format:
{
  "keywords": ["list of main topic keywords"],
  "categories": ["list of matching category names"]
}

EXAMPLE QUERIES:
- "journey related post": { "keywords": ["travel"], "categories": ["Lifestyle"] }
- "posts about a journey": { "keywords": ["travel"], "categories": ["Lifestyle"] }
- "traveling posts": { "keywords": ["traveling", "travel"], "categories": ["Lifestyle"] }
- "posts by sohag about tech": { "keywords": ["sohag", "tech"], "categories": ["Tech"] }
- "Wanderlust Chronicles": { "keywords": ["Wanderlust Chronicles", "travel"], "categories": ["Lifestyle"] }
- "a post about learning": { "keywords": ["learning", "education"], "categories": ["Education"] }

If no keywords or categories match, return empty arrays:
{ "keywords": [], "categories": [] }
Do not add any other text or explanations.`;
            // --- END OF PROMPT ---

            const payload = {
                contents: [{ parts: [{ text: query }] }],
                systemInstruction: {
                    parts: [{ text: systemPrompt }]
                },
                generationConfig: {
                    responseMimeType: "application/json"
                }
            };

            const data = await fetchWithBackoff(geminiApiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const jsonText = data.candidates[0].content.parts[0].text;
            try {
                return JSON.parse(jsonText); 
            } catch (parseError) {
                console.error("Failed to parse AI response:", jsonText);
                throw new Error("AI returned invalid JSON. Check the console.");
            }
        }

        /**
         * Step 2: Calls our Laravel backend with both lists
         */
        async function getPostsFromBackend(keywords, categories) {
            const response = await fetch(backendSearchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    keywords: keywords,
                    categories: categories
                })
            });
            if (!response.ok) {
                throw new Error(`Backend request failed with status ${response.status} (${response.statusText})`);
            }
            return await response.json();
        }

        /**
         * Step 3: Renders the post cards (unchanged)
         */
        function renderPosts(posts) {
            let html = '';
            posts.forEach(post => {
                const postDate = new Date(post.created_at).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric'
                });
                
                const postUrl = `${postDetailBaseUrl}/${post.id}`;

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

        // --- Helper Functions (UNCHANGED) ---

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

        async function fetchWithBackoff(url, options, retries = 3, delay = 1000) {
            try {
                const response = await fetch(url, options);
                if (!response.ok) {
                    if (response.status === 429 && retries > 0) {
                        await new Promise(resolve => setTimeout(resolve, delay));
                        return fetchWithBackoff(url, options, retries - 1, delay * 2); 
                    }
                    const errorText = await response.text();
                    console.error("API Error Response:", errorText);
                    throw new Error(`Gemini API request failed with status ${response.status}. See console for response.`);
                }
                return await response.json();
            } catch (error) {
                if (retries > 0) {
                    await new Promise(resolve => setTimeout(resolve, delay));
                    return fetchWithBackoff(url, options, retries - 1, delay * 2);
                }
                console.error("Failed to fetch after multiple retries:", error);
                throw error;
            }
        }

    </script>

</body>
</html>

