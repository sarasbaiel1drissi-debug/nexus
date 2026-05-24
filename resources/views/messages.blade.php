@extends('include.master')
@section('page', 'Messages')
@section('content')

<style>
    #chat-app {
        background: #101522;
        border: 1px solid rgba(255,255,255,.08);
    }

    .conversation-button:hover,
    .conversation-button.is-active {
        background: #1f293d !important;
    }

    #messages-list {
        background: #0c111d;
    }

    .chat-message-bubble {
        max-width: 70%;
        border-radius: 18px;
        padding: 12px 14px;
        border: 1px solid transparent;
    }

    .chat-message-bubble.sent {
        background: #7c3aed;
        color: #fff;
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.24);
    }

    .chat-message-bubble.received {
        background: #1b2333;
        color: #edf2ff;
        border-color: rgba(255,255,255,.08);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
    }

    .chat-message-bubble.pending {
        opacity: .72;
    }

    .chat-message-time.sent {
        color: rgba(255, 255, 255, 0.72);
    }

    .chat-message-time.received {
        color: #9aa7bd;
    }

    .chat-shell-panel {
        background: #121827;
    }

    .chat-sidebar {
        background: #151c2d;
        border-color: rgba(255,255,255,.08) !important;
    }

    .chat-border {
        border-color: rgba(255,255,255,.08) !important;
    }

    .chat-muted {
        color: #9aa7bd !important;
    }

    .chat-title {
        color: #f8fafc !important;
    }

    .chat-unread-badge {
        width: 16px;
        height: 16px;
        min-width: 16px;
        padding: 0;
        font-size: 9px;
        line-height: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    @media (max-width: 767px) {
        #chat-app {
            flex-direction: column;
            height: calc(100vh - 160px) !important;
        }

        #chat-app > .border-end {
            width: 100% !important;
            max-height: 210px;
            border-right: 0 !important;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        #chat-app > .d-flex.flex-column {
            width: 100% !important;
            min-height: 360px;
        }
    }
</style>

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 col-lg-12">
                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        <h2 class="fw-700 mb-0 mt-0 font-md text-grey-900 d-flex align-items-center">
                            Messages
                        </h2>
                    </div>
                </div>

                <div class="col-xl-12 col-xxl-12 col-lg-12">
                    <div id="chat-app"
                         data-initial-user-id="{{ $userId }}"
                         data-current-user-id="{{ auth()->id() }}"
                         data-conversations-url="{{ route('chat.conversations') }}"
                         data-open-url="{{ url('/chat/open') }}"
                         data-csrf="{{ csrf_token() }}"
                         class="d-flex rounded-xxl shadow-xss overflow-hidden"
                         style="height:calc(100vh - 200px);">
                        <div class="border-end overflow-auto chat-sidebar" style="width:35%;">
                            <div class="p-3 border-bottom fw-700 font-xss chat-title chat-border">
                                Conversations
                            </div>
                            <ul id="conversation-list" class="list-unstyled mb-0"></ul>
                        </div>

                        <div class="d-flex flex-column chat-shell-panel" style="width:65%;">
                            <div id="chat-empty" class="d-flex align-items-center justify-content-center h-100 chat-muted">
                                <p class="font-xss">Select a conversation or start a new one.</p>
                            </div>

                            <div id="chat-panel" class="d-none flex-column h-100">
                                <div class="p-3 border-bottom d-flex align-items-center shadow-xs chat-border">
                                    <a id="chat-avatar-link" href="#" class="d-inline-flex me-3">
                                        <img id="chat-avatar" src="" alt="" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                    </a>
                                    <div>
                                        <h4 id="chat-name" class="fw-700 font-xssss chat-title mb-0"></h4>
                                        <a id="chat-profile" href="#" class="font-xssss text-current"></a>
                                    </div>
                                </div>

                                <div id="messages-list" class="flex-grow-1 overflow-auto p-3"></div>

                                <div class="p-3 border-top chat-border">
                                    <form id="message-form" class="d-flex gap-2">
                                        <input id="message-input" type="text" placeholder="Type your message..." autocomplete="off" class="form-control rounded-xxl border-light font-xssss flex-grow-1">
                                        <button type="submit" class="btn bg-current text-white rounded-xxl font-xssss fw-600 px-4">
                                            Send
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const app = document.getElementById('chat-app');
    const state = {
        conversations: [],
        activeConversation: null,
        lastMessageId: 0,
        stream: null,
        pollTimer: null,
        channel: null,
        pending: new Map(),
    };

    const els = {
        list: document.getElementById('conversation-list'),
        empty: document.getElementById('chat-empty'),
        panel: document.getElementById('chat-panel'),
        avatarLink: document.getElementById('chat-avatar-link'),
        avatar: document.getElementById('chat-avatar'),
        name: document.getElementById('chat-name'),
        profile: document.getElementById('chat-profile'),
        messages: document.getElementById('messages-list'),
        form: document.getElementById('message-form'),
        input: document.getElementById('message-input'),
    };

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));

    const nowTime = () => new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    const makeClientId = () => `client-${Date.now()}-${Math.random().toString(16).slice(2)}`;

    const normalizeMessage = (message, clientId = null) => ({
        ...message,
        client_id: message.client_id || clientId,
        created_at: message.created_at ? String(message.created_at).slice(0, 5) : nowTime(),
        is_me: message.is_me ?? Number(message.sender_id) === Number(app.dataset.currentUserId),
    });

    const updateGlobalMessageBadges = (totalUnread) => {
        document.querySelectorAll('.nexus-message-count').forEach((badge) => {
            if (totalUnread > 0) {
                badge.textContent = totalUnread > 9 ? '9+' : totalUnread;
                badge.classList.remove('d-none');
            } else {
                badge.remove();
            }
        });

        if (totalUnread <= 0) {
            document.querySelectorAll('.nexus-message-dot').forEach((dot) => dot.remove());
        }
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': app.dataset.csrf,
                ...(options.headers || {}),
            },
            ...options,
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        return response.json();
    };

    const renderConversations = () => {
        els.list.innerHTML = state.conversations.map((conversation) => {
            const active = state.activeConversation?.id === conversation.id;
            const unread = conversation.unread_count > 0;
            const lastMessage = conversation.last_message?.body || 'Say hello!';

            return `
                <li class="border-bottom chat-border">
                    <button type="button" class="w-100 bg-transparent border-0 text-start p-3 conversation-button ${active ? 'is-active' : ''}" data-id="${conversation.id}" style="cursor:pointer;">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <img src="${escapeHtml(conversation.user.avatar)}" alt="${escapeHtml(conversation.user.name)}" class="rounded-circle me-3" style="width:40px;height:40px;object-fit:cover;">
                                ${conversation.user.is_online ? '<span class="position-absolute rounded-circle bg-success" style="width:8px;height:8px;bottom:2px;right:14px;border:1px solid #151c2d;"></span>' : ''}
                            </div>
                            <div class="overflow-hidden flex-grow-1">
                                <h5 class="${unread ? 'fw-700' : 'fw-600'} font-xssss chat-title mb-0 text-truncate">${escapeHtml(conversation.user.name)}</h5>
                                <p class="${unread ? 'fw-700 text-white' : 'fw-400 chat-muted'} font-xssss mb-0 text-truncate">${escapeHtml(lastMessage)}</p>
                            </div>
                            ${unread ? `<span class="bg-danger text-white rounded-circle font-xsssss fw-700 ms-2 chat-unread-badge">${conversation.unread_count > 9 ? '9+' : conversation.unread_count}</span>` : ''}
                        </div>
                    </button>
                </li>
            `;
        }).join('') || '<li class="p-4 text-center font-xssss chat-muted">No conversations yet.</li>';
    };

    const appendMessage = (rawMessage) => {
        const message = normalizeMessage(rawMessage, rawMessage.client_id || null);

        document.getElementById('chat-no-messages')?.remove();

        if (message.client_id && state.pending.has(message.client_id)) {
            document.querySelector(`[data-message-client-id="${message.client_id}"]`)?.remove();
            state.pending.delete(message.client_id);
        }

        if (message.id && document.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        if (message.id) {
            state.lastMessageId = Math.max(state.lastMessageId, Number(message.id));
        }

        els.messages.insertAdjacentHTML('beforeend', `
            <div data-message-id="${message.id || ''}" data-message-client-id="${message.client_id || ''}" class="d-flex mb-3 ${message.is_me ? 'justify-content-end' : 'justify-content-start'}">
                <div class="chat-message-bubble ${message.is_me ? 'sent' : 'received'} ${message.pending ? 'pending' : ''}">
                    <p class="font-xssss mb-1">${escapeHtml(message.body)}</p>
                    <span class="d-block text-end chat-message-time ${message.is_me ? 'sent' : 'received'}" style="font-size:10px;">${escapeHtml(message.created_at)}</span>
                </div>
            </div>
        `);

        els.messages.scrollTop = els.messages.scrollHeight;
    };

    const renderMessages = (messages) => {
        els.messages.innerHTML = '';

        if (!messages.length) {
            els.messages.innerHTML = '<div id="chat-no-messages" class="d-flex align-items-center justify-content-center h-100 chat-muted font-xssss"><p>No messages yet. Start the conversation.</p></div>';
            state.lastMessageId = 0;
            return;
        }

        messages.forEach(appendMessage);
    };

    const refreshConversations = async () => {
        state.conversations = await request(app.dataset.conversationsUrl);
        updateGlobalMessageBadges(state.conversations.reduce((sum, conversation) => sum + Number(conversation.unread_count || 0), 0));
        renderConversations();
    };

    const refreshConversationsQuietly = () => {
        refreshConversations().catch(() => {});
    };

    const updateActiveConversationPreview = (message) => {
        if (!state.activeConversation) {
            return;
        }

        const conversation = state.conversations.find((item) => item.id === state.activeConversation.id);

        if (conversation) {
            conversation.last_message = message;
            renderConversations();
        }
    };

    const stopRealtime = () => {
        if (state.stream) {
            state.stream.close();
            state.stream = null;
        }

        if (state.pollTimer) {
            clearInterval(state.pollTimer);
            state.pollTimer = null;
        }

        if (state.channel && window.Echo && state.activeConversation) {
            window.Echo.leave(`conversations.${state.activeConversation.id}`);
            state.channel = null;
        }
    };

    const pollNewMessages = async () => {
        if (!state.activeConversation) {
            return;
        }

        const messages = await request(`/chat/conversations/${state.activeConversation.id}/messages?after_id=${state.lastMessageId}`);

        if (!messages.length) {
            return;
        }

        messages.forEach(appendMessage);
        updateActiveConversationPreview(messages[messages.length - 1]);
        request(`/chat/conversations/${state.activeConversation.id}/read`, { method: 'POST', body: JSON.stringify({}) })
            .then(({ total_unread }) => updateGlobalMessageBadges(total_unread))
            .catch(() => {});
        refreshConversationsQuietly();
    };

    const startPolling = () => {
        if (!state.activeConversation) {
            return;
        }

        pollNewMessages().catch(() => {});
        state.pollTimer = setInterval(() => {
            pollNewMessages().catch(() => {});
        }, 1200);
    };

    const startRealtime = () => {
        stopRealtime();

        if (!state.activeConversation) {
            return;
        }

        if (window.Echo) {
            state.channel = window.Echo.private(`conversations.${state.activeConversation.id}`)
                .listen('.message.sent', async (event) => {
                    if (event.message) {
                        appendMessage(normalizeMessage(event.message, event.client_id || null));
                        updateActiveConversationPreview(event.message);
                        request(`/chat/conversations/${state.activeConversation.id}/read`, { method: 'POST', body: JSON.stringify({}) })
                            .then(({ total_unread }) => updateGlobalMessageBadges(total_unread))
                            .catch(() => {});
                        refreshConversationsQuietly();
                    }
                });
        }

        startPolling();
    };

    const openConversation = async (conversation) => {
        stopRealtime();
        state.activeConversation = conversation;
        conversation.unread_count = 0;
        state.lastMessageId = 0;

        els.empty.classList.add('d-none');
        els.panel.classList.remove('d-none');
        els.panel.classList.add('d-flex');
        els.avatarLink.href = conversation.user.profile_url;
        els.avatar.src = conversation.user.avatar;
        els.avatar.alt = conversation.user.name;
        els.name.textContent = conversation.user.name;
        els.profile.href = conversation.user.profile_url;
        els.profile.textContent = `@${conversation.user.username}`;

        renderConversations();
        renderMessages(await request(`/chat/conversations/${conversation.id}/messages`));
        await request(`/chat/conversations/${conversation.id}/read`, { method: 'POST', body: JSON.stringify({}) });
        await refreshConversations();
        startRealtime();
    };

    els.list.addEventListener('click', (event) => {
        const button = event.target.closest('.conversation-button');

        if (!button) {
            return;
        }

        const conversation = state.conversations.find((item) => item.id === Number(button.dataset.id));

        if (conversation) {
            openConversation(conversation);
        }
    });

    els.form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const body = els.input.value.trim();

        if (!body || !state.activeConversation) {
            return;
        }

        const clientId = makeClientId();
        els.input.value = '';
        state.pending.set(clientId, true);
        appendMessage({
            id: null,
            client_id: clientId,
            conversation_id: state.activeConversation.id,
            sender_id: Number(app.dataset.currentUserId),
            body,
            created_at: nowTime(),
            is_me: true,
            pending: true,
        });

        try {
            const savedMessage = await request(`/chat/conversations/${state.activeConversation.id}/messages`, {
                method: 'POST',
                body: JSON.stringify({ body, client_id: clientId }),
            });

            appendMessage(savedMessage);
            updateActiveConversationPreview(savedMessage);
            refreshConversationsQuietly();
        } catch (error) {
            document.querySelector(`[data-message-client-id="${clientId}"] .chat-message-bubble`)?.classList.add('border-danger');
            state.pending.delete(clientId);
            els.input.value = body;
        }
    });

    window.addEventListener('beforeunload', stopRealtime);

    (async () => {
        await refreshConversations();

        if (app.dataset.initialUserId) {
            const conversation = await request(`${app.dataset.openUrl}/${app.dataset.initialUserId}`, { method: 'POST' });
            await refreshConversations();
            await openConversation(conversation);
        }
    })();
});
</script>

@endsection
