<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Tempat Pulang')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')

    <style>
        /* Mencegah layar kedip saat loading sidebar */
        .sidebar.collapsed { width: 0; overflow: hidden; }
        .main.full-width { margin-left: 0; width: 100%; }
        
        /* Memastikan area konten punya ruang */
        .content { padding: 20px; min-height: calc(100vh - 90px); }

        /* --- NOTIFICATION STYLES --- */
        .notification-dropdown { position: relative; display: inline-block; }
        
        .notif-badge {
            position: absolute; top: 2px; right: 2px;
            width: 10px; height: 10px;
            background: #e53e3e; border-radius: 50%;
            border: 2px solid white; display: block;
        }

        .notif-menu {
            position: absolute; top: 55px; right: 0;
            width: 320px; background: white;
            border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid #f0f0f0; display: none;
            z-index: 1000; overflow: hidden;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notif-header {
            padding: 15px 20px; border-bottom: 1px solid #f5f5f5;
            display: flex; justify-content: space-between; align-items: center;
        }

        .notif-header span { font-weight: 700; color: #4A3A35; }
        .notif-header button { background: none; border: none; color: #E2A695; font-size: 12px; cursor: pointer; }

        .notif-content { max-height: 350px; overflow-y: auto; }

        .notif-item {
            display: flex; gap: 15px; padding: 15px 20px;
            transition: 0.3s; cursor: pointer; border-bottom: 1px solid #fcfcfc;
        }

        .notif-item:hover { background: #FDF0ED; }
        .notif-item.unread { background: #fff9f8; }

        .notif-icon-box {
            width: 40px; height: 40px; background: #FDF0ED;
            color: #E2A695; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        .notif-text p { font-size: 13px; color: #5c5045; margin: 0; line-height: 1.4; }
        .notif-text small { color: #bbb; font-size: 11px; }

        .notif-footer { padding: 12px; text-align: center; background: #fcfcfc; border-top: 1px solid #f5f5f5; }
        .notif-footer a { font-size: 12px; color: #E2A695; text-decoration: none; font-weight: 600; }
    </style>
</head>

<body>

<div class="container">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logoku.png') }}" alt="Logo Tempat Pulang">
        </div>

        <div class="sidebar-content" style="display: flex; flex-direction: column; justify-content: space-between; height: calc(100% - 90px);">
            <nav class="menu">
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house menu-icon"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('mood.index') }}" class="menu-item {{ request()->routeIs('mood.index') ? 'active' : '' }}">
                    <i class="fa-regular fa-face-smile menu-icon"></i>
                    <span>Mood Hari Ini</span>
                </a>
                <a href="{{ route('curhat.index') }}" class="menu-item {{ request()->routeIs('curhat.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-book-open menu-icon"></i>
                    <span>Ruang Curhat</span>
                </a>
                <a href="{{ route('mood.calendar') }}" class="menu-item {{ request()->routeIs('mood.calendar') ? 'active' : '' }}">
                    <i class="fa-regular fa-calendar menu-icon"></i>
                    <span>Kalender Mood</span>
                </a>
                <a href="{{ route('kenangan.index') }}" class="menu-item {{ request()->routeIs('kenangan.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open menu-icon"></i>
                    <span>Kotak Kenangan</span>
                </a>
            </nav>

            <nav class="menu menu-bottom" style="border-top: 1px solid rgba(0,0,0,0.05); padding-top: 15px; margin-bottom: 20px;">
                <a href="{{ route('profil.edit') }}" class="menu-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear menu-icon"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="main" id="main-content">
        <header class="header">
            <div class="header-left">
                <div class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>

            <div class="header-right">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <div class="header-avatar">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=F1BCAE&color=fff' }}" alt="Profile Photo">
                </div>
                
                <div class="notification-dropdown">
                    <div class="icon-circle" id="notifButton" onclick="toggleNotif()" style="cursor: pointer;">
                        <i class="fa-regular fa-bell header-icon"></i>
                        <span class="notif-badge" id="notifBadge"></span>
                    </div>

                    <div class="notif-menu" id="notifMenu">
                        <div class="notif-header">
                            <span>Notifikasi</span>
                            <button onclick="clearNotif()">Hapus Semua</button>
                        </div>
                        <div class="notif-content" id="notifContent">
                            <div class="notif-item unread">
                                <div class="notif-icon-box"><i class="fa-solid fa-heart"></i></div>
                                <div class="notif-text">
                                    <p>Halo {{ auth()->user()->name }}, sudahkah kamu berterima kasih pada dirimu hari ini? ✨</p>
                                    <small>Baru saja</small>
                                </div>
                            </div>
                            <div class="notif-item">
                                <div class="notif-icon-box"><i class="fa-solid fa-pen-fancy"></i></div>
                                <div class="notif-text">
                                    <p>Jangan lupa tuangkan perasaanmu di Mood Hari Ini ya. 🌸</p>
                                    <small>2 jam yang lalu</small>
                                </div>
                            </div>
                        </div>
                        <div class="notif-footer">
                            <a href="#">Tandai sudah dibaca</a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="logout-form" style="display:inline;">
                    @csrf
                    <button type="submit" class="icon-circle" style="border:none; cursor:pointer;">
                        <i class="fa-solid fa-arrow-right-from-bracket header-icon"></i>
                    </button>
                </form>
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>
    </main>
</div>

<button class="chat-trigger" onclick="openChat()">
    <i class="fa-solid fa-comments"></i>
</button>

<div class="chat-modal" id="chatModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px); z-index: 999999; align-items: center; justify-content: center;">
    
    <div class="chat-container" style="width: 90%; max-width: 500px; height: 80vh; background: white; border-radius: 35px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.3);">
        
        <div class="chat-header" style="background: #E2A695; padding: 20px 25px; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div class="header-info">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Teman Bicara 🤍</h3>
                <small style="opacity: 0.9;">Online | Siap mendengarkanmu</small>
            </div>
            <button class="close-chat" onclick="closeChat()" style="background: none; border: none; color: white; font-size: 30px; cursor: pointer;">&times;</button>
        </div>
        
        <div class="chat-body" id="chatBody" style="flex: 1; padding: 25px; overflow-y: auto; background: #fdfaf9; display: flex; flex-direction: column; gap: 15px;">
            <div class="msg bot-msg" style="background: #f1f1f1; color: #4A3A35; padding: 12px 18px; border-radius: 20px; align-self: flex-start; max-width: 85%;">
                Halo! Aku Teman Bicara. Kalau ada yang mengganjal di hati, ceritain ke aku aja ya... 😊
            </div>
        </div>

        <div class="chat-footer" style="padding: 20px; background: white; border-top: 1px solid #eee; display: flex; gap: 10px;">
            <input type="text" id="chatInput" placeholder="Tulis sesuatu..." onkeypress="handleKeyPress(event)" style="flex: 1; border: none; background: #f5f5f5; padding: 12px 20px; border-radius: 25px; outline: none;">
            <button onclick="sendMessage()" style="background: #E2A695; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
    // 1. Sidebar Toggle
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");

    if(menuToggle) {
        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            mainContent.classList.toggle("full-width");
        });
    }

    // 2. NOTIFICATION LOGIC
    function toggleNotif() {
        const menu = document.getElementById('notifMenu');
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
        } else {
            menu.style.display = 'block';
        }
    }

    function clearNotif() {
        document.getElementById('notifContent').innerHTML = '<div style="padding:40px; text-align:center; color:#ccc; font-size:13px;">Belum ada notifikasi baru.</div>';
        const badge = document.getElementById('notifBadge');
        if(badge) badge.style.display = 'none';
    }

    window.addEventListener('click', function(event) {
        if (!event.target.closest('.notification-dropdown')) {
            const menu = document.getElementById('notifMenu');
            if (menu) menu.style.display = 'none';
        }
    });

    // 3. Tab System (Global)
    function openTab(evt, tabName) {
        const tabcontent = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }
        const tablinks = document.getElementsByClassName("tab-btn");
        for (let i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        const targetTab = document.getElementById(tabName);
        if(targetTab) {
            targetTab.style.display = "block";
            setTimeout(() => targetTab.classList.add("active"), 10);
        }
        evt.currentTarget.classList.add("active");
    }

function openChat() {
    const modal = document.getElementById('chatModal');
    // Paksa display menjadi flex agar align-items: center bekerja
    modal.style.display = 'flex'; 
    loadHistory();
}

function closeChat() {
    const modal = document.getElementById('chatModal');
    modal.style.display = 'none';
}

    function handleKeyPress(e) {
        if (e.key === 'Enter') sendMessage();
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;

        appendMsg(msg, 'user-msg');
        input.value = '';

        const loadingId = appendMsg('Sedang mengetik...', 'bot-msg');

        try {
            const response = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });

            const data = await response.json();
            document.getElementById(loadingId).innerText = data.message;
        } catch (error) {
            document.getElementById(loadingId).innerText = "Maaf, koneksiku terganggu.. 😔";
        }
    }

    function appendMsg(text, type) {
        const body = document.getElementById('chatBody');
        const div = document.createElement('div');
        const id = 'msg-' + Date.now();
        div.id = id;
        div.className = `msg ${type}`;
        div.innerText = text;
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
        return id;
    }

    async function loadHistory() {
        try {
            const response = await fetch("{{ route('chat.history') }}");
            const data = await response.json();
            const body = document.getElementById('chatBody');
            
            if(data.length > 0) {
                body.innerHTML = ''; 
                data.forEach(chat => {
                    appendMsg(chat.message, chat.is_bot ? 'bot-msg' : 'user-msg');
                });
            }
        } catch (error) {
            console.error("Gagal memuat riwayat:", error);
        }
    }
</script>

@auth
<script>
    // 5. Time Tracker
    let isActive = !document.hidden;
    let totalSeconds = localStorage.getItem('active_time') ? parseInt(localStorage.getItem('active_time')) : {{ auth()->user()->total_time ?? 0 }};

    function updateDisplay() {
        let hrs = Math.floor(totalSeconds / 3600);
        let mins = Math.floor((totalSeconds % 3600) / 60);
        let secs = totalSeconds % 60;
        const pad = (num) => num.toString().padStart(2, '0');
        if (document.getElementById('hours')) {
            document.getElementById('hours').innerText = pad(hrs);
            document.getElementById('minutes').innerText = pad(mins);
            document.getElementById('seconds').innerText = pad(secs);
        }
    }

    setInterval(() => {
        if (isActive) {
            totalSeconds++;
            localStorage.setItem('active_time', totalSeconds);
            updateDisplay();
        }
    }, 1000);

    document.addEventListener("visibilitychange", () => { isActive = !document.hidden; });

    setInterval(() => {
        if (isActive) {
            fetch("{{ route('update.time') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ seconds: 30 })
            });
        }
    }, 30000);
    
    updateDisplay();
</script>
@endauth

@stack('scripts')

</body>
</html>