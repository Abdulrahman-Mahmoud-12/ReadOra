<x-layouts.app title="Ask ReadOra AI — Virtual Librarian & Literary Assistant">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        {{-- Header Banner --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 dark:border-navy-800 pb-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 text-gold-600 dark:text-gold-400 text-xs font-semibold uppercase tracking-wider mb-2">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    Powered by OpenRouter LLM
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    Ask ReadOra AI Librarian
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
                    Your personal literary guide for deep book recommendations, concept summaries, chapter breakdowns, and reading advice.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Librarian Online
                </span>
            </div>
        </div>

        {{-- Main Chat Container --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Chat Stream Window (8 Cols) --}}
            <div class="lg:col-span-8 flex flex-col h-[650px] rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-navy-800 dark:bg-navy-900 overflow-hidden">
                {{-- Message History Scroll Area --}}
                <div id="chat-messages" class="flex-1 p-6 overflow-y-auto space-y-6 bg-gray-50/50 dark:bg-navy-950/40">
                    {{-- Welcome Message from AI --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-navy-950 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                            AI
                        </div>
                        <div class="max-w-[85%] rounded-2xl bg-white dark:bg-navy-900 p-4 border border-gray-200 dark:border-navy-800 shadow-sm text-xs leading-relaxed text-gray-800 dark:text-gray-200 space-y-2">
                            <p class="font-bold text-navy-950 dark:text-gold-300">Welcome to ReadOra! I'm your AI Librarian.</p>
                            <p>I have access to our digital collection of literature, computer science, philosophy, and history. Ask me for book recommendations, explanations of complex concepts, or guidance on what to explore next!</p>
                        </div>
                    </div>
                </div>

                {{-- Suggested Quick Prompts --}}
                <div class="p-3 bg-white dark:bg-navy-900 border-t border-gray-100 dark:border-navy-800">
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-[11px]">
                        <span class="text-gray-400 font-bold uppercase tracking-wider shrink-0 text-[10px]">Try:</span>
                        <button type="button" onclick="sendQuickPrompt('What are the best computer science and software architecture books in our catalog?')" class="px-3 py-1 rounded-full bg-navy-50 hover:bg-gold-50 dark:bg-navy-800 dark:hover:bg-navy-700 text-navy-800 dark:text-gold-300 border border-navy-100 dark:border-navy-700 whitespace-nowrap transition-colors">
                            💻 Best Software Architecture Books
                        </button>
                        <button type="button" onclick="sendQuickPrompt('Explain the core message of Clean Code by Robert Martin.')" class="px-3 py-1 rounded-full bg-navy-50 hover:bg-gold-50 dark:bg-navy-800 dark:hover:bg-navy-700 text-navy-800 dark:text-gold-300 border border-navy-100 dark:border-navy-700 whitespace-nowrap transition-colors">
                            📖 Summarize Clean Code
                        </button>
                        <button type="button" onclick="sendQuickPrompt('Recommend a fast-paced thriller or science fiction book for the weekend.')" class="px-3 py-1 rounded-full bg-navy-50 hover:bg-gold-50 dark:bg-navy-800 dark:hover:bg-navy-700 text-navy-800 dark:text-gold-300 border border-navy-100 dark:border-navy-700 whitespace-nowrap transition-colors">
                            🚀 Recommend a Sci-Fi Thriller
                        </button>
                    </div>
                </div>

                {{-- Input Bar Form --}}
                <div class="p-4 bg-white dark:bg-navy-900 border-t border-gray-200 dark:border-navy-800">
                    <form id="ai-chat-form" onsubmit="handleChatSubmit(event)" class="flex gap-2">
                        <input
                            type="text"
                            id="user-input"
                            placeholder="Ask about any book, theme, genre, or topic..."
                            required
                            autocomplete="off"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-gray-300 dark:border-navy-700 bg-gray-50 dark:bg-navy-950 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-gold-400"
                        />
                        <button
                            type="submit"
                            id="send-button"
                            class="px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-navy-950 font-bold rounded-xl text-xs shadow-sm transition-all flex items-center gap-1.5"
                        >
                            <span>Send</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sidebar: Collection Highlights (4 Cols) --}}
            <aside class="lg:col-span-4 space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-navy-800 dark:bg-navy-900">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Catalog Highlights
                    </h3>
                    <div class="space-y-3">
                        @foreach($featuredBooks as $fb)
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-navy-800 transition-colors">
                                <div class="w-10 h-14 bg-navy-950 rounded overflow-hidden shadow-sm shrink-0">
                                    @if($fb->cover_image_path)
                                        <img src="{{ $fb->cover_image_path }}" alt="{{ $fb->title }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-[8px] text-gold-400 font-bold">Book</div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('books.show', $fb->slug) }}" class="text-xs font-bold text-gray-900 dark:text-white hover:underline truncate block">
                                        {{ $fb->title }}
                                    </a>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 truncate block">
                                        {{ $fb->authors->pluck('name')->join(', ') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gold-500">★ {{ number_format($fb->average_rating, 1) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-gold-500/30 bg-gold-500/5 p-6 shadow-sm">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gold-600 dark:text-gold-400 mb-2">How It Works</h4>
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                        ReadOra AI uses the OpenRouter language intelligence model grounded with real catalog bibliographic records to provide context-aware answers and recommendations.
                    </p>
                </div>
            </aside>
        </div>
    </div>

    {{-- Interactive Chat JavaScript --}}
    <script>
        const chatHistory = [];
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const sendButton = document.getElementById('send-button');

        function appendMessage(role, content) {
            const isUser = role === 'user';
            const wrapper = document.createElement('div');
            wrapper.className = `flex items-start gap-3 ${isUser ? 'justify-end' : ''}`;

            if (isUser) {
                wrapper.innerHTML = `
                    <div class="max-w-[85%] rounded-2xl bg-navy-900 text-white p-4 shadow-sm text-xs leading-relaxed">
                        ${escapeHtml(content)}
                    </div>
                    <div class="w-8 h-8 rounded-full bg-navy-800 text-gold-400 font-bold text-xs flex items-center justify-center shrink-0 border border-navy-700">
                        You
                    </div>
                `;
            } else {
                wrapper.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-navy-950 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                        AI
                    </div>
                    <div class="max-w-[85%] rounded-2xl bg-white dark:bg-navy-900 p-4 border border-gray-200 dark:border-navy-800 shadow-sm text-xs leading-relaxed text-gray-800 dark:text-gray-200 whitespace-pre-line prose-sm dark:prose-invert">
                        ${formatMarkdown(content)}
                    </div>
                `;
            }

            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function appendTypingIndicator() {
            const typing = document.createElement('div');
            typing.id = 'typing-indicator';
            typing.className = 'flex items-start gap-3';
            typing.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 text-navy-950 font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md">
                    AI
                </div>
                <div class="rounded-2xl bg-white dark:bg-navy-900 p-3 border border-gray-200 dark:border-navy-800 shadow-sm flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            `;
            chatMessages.appendChild(typing);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        async function handleChatSubmit(event) {
            if (event) event.preventDefault();
            const message = userInput.value.trim();
            if (!message) return;

            userInput.value = '';
            userInput.disabled = true;
            sendButton.disabled = true;

            appendMessage('user', message);
            chatHistory.push({ role: 'user', content: message });

            appendTypingIndicator();

            try {
                const response = await fetch("{{ route('assistant.chat') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message,
                        history: chatHistory.slice(-6)
                    })
                });

                const data = await response.json();
                removeTypingIndicator();

                if (data.success && data.message) {
                    appendMessage('assistant', data.message);
                    const assistantMessage = { role: 'assistant', content: data.message };
                    if (data.reasoning_details) {
                        assistantMessage.reasoning_details = data.reasoning_details;
                    }
                    chatHistory.push(assistantMessage);
                } else {
                    appendMessage('assistant', 'Sorry, I could not process your request at this time. Please try again.');
                }
            } catch (err) {
                removeTypingIndicator();
                appendMessage('assistant', 'Network communication error. Please check your connection and try again.');
            } finally {
                userInput.disabled = false;
                sendButton.disabled = false;
                userInput.focus();
            }
        }

        function sendQuickPrompt(promptText) {
            userInput.value = promptText;
            handleChatSubmit();
        }

        function escapeHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function formatMarkdown(text) {
            // Simple markdown formatter for bold, bullets and newlines
            let formatted = escapeHtml(text);
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/^\s*•\s*(.*)$/gm, '• $1');
            return formatted;
        }
    </script>
</x-layouts.app>
