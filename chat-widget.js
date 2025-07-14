// ---- Chat Widget Logic (shared for userPanel and adminPanel) ----
(function() {
  // Only run if chatWidget exists
  if (!document.getElementById('chatWidget')) return;

  // Detect panel type
  var sender = window.sender;
  var receiver = window.receiver;
  var isUserPanel = typeof sender !== 'undefined' && typeof receiver !== 'undefined' && receiver === 'admin';
  var isAdminPanel = typeof sender !== 'undefined' && sender === 'admin';

  // --- USER PANEL CHAT LOGIC ---
  if (isUserPanel) {
    let chatMinimized = false;
    let lastMessageId = null;

    function fetchMessages() {
      fetch(`getMessages.php?sender=${encodeURIComponent(sender)}&receiver=${encodeURIComponent(receiver)}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const chat = document.getElementById('chatMessages');
            // Only scroll if user is already at (or near) the bottom
const isAtBottom = (chat.scrollHeight - chat.scrollTop - chat.clientHeight) < 20;
chat.innerHTML = data.messages.map(msg =>
  `<div style='margin-bottom:10px;text-align:${msg.sender===sender?'right':'left'};'>
    <span style='display:inline-block;padding:6px 12px;border-radius:16px;max-width:75%;background:${msg.sender===sender?'#2957A4':'#e0e0e0'};color:${msg.sender===sender?'#fff':'#222'};'>${msg.message}</span>
    <div style='font-size:11px;color:#888;'>${msg.sender} - ${msg.sent_at}</div>
  </div>`
).join('');
if (isAtBottom) chat.scrollTop = chat.scrollHeight;
            // Notification logic
            // Get last seen admin message id from localStorage
            let lastSeenAdminMsgId = localStorage.getItem('userLastSeenAdminMsgId');
            let latestAdminMsgId = null;
            let unreadCount = 0;
            for (let i = 0; i < data.messages.length; i++) {
              if (data.messages[i].sender === 'admin') {
                latestAdminMsgId = data.messages[i].id;
                if (!lastSeenAdminMsgId || data.messages[i].id > lastSeenAdminMsgId) {
                  unreadCount++;
                }
              }
            }
            const notifBadge = document.getElementById('chatNotifBadge');
            if (chatMinimized) {
              if (unreadCount > 0) {
                notifBadge.style.display = 'block';
                notifBadge.textContent = unreadCount;
              } else {
                notifBadge.style.display = 'none';
                notifBadge.textContent = '';
              }
            } else if (!chatMinimized && data.messages.length > 0) {
              // When chat is opened, update last seen admin message id
              if (latestAdminMsgId) {
                localStorage.setItem('userLastSeenAdminMsgId', latestAdminMsgId);
              }
              notifBadge.style.display = 'none';
              notifBadge.textContent = '';
            }
          }
        });
    }

    document.getElementById('sendChatBtn').onclick = function() {
      const input = document.getElementById('chatInput');
      const msg = input.value.trim();
      if (!msg) return;
      fetch('sendMessage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `sender=${encodeURIComponent(sender)}&receiver=${encodeURIComponent(receiver)}&message=${encodeURIComponent(msg)}`
      }).then(res => res.json()).then(data => {
        if (data.success) {
          input.value = '';
          fetchMessages();
        }
      });
    };

    document.getElementById('chatInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') document.getElementById('sendChatBtn').click();
    });

    // Wait for DOM to be fully loaded before running chat widget logic
    document.addEventListener('DOMContentLoaded', function() {
      var minimized = localStorage.getItem('chatMinimized');
      if (minimized === 'true') {
        document.getElementById('chatWidget').style.display = 'none';
        document.getElementById('chatWidgetMin').style.display = 'block';
        chatMinimized = true;
      } else {
        document.getElementById('chatWidget').style.display = 'block';
        document.getElementById('chatWidgetMin').style.display = 'none';
        chatMinimized = false;
      }

      document.getElementById('minimizeChatBtn').onclick = function() {
        document.getElementById('chatWidget').style.display = 'none';
        document.getElementById('chatWidgetMin').style.display = 'block';
        chatMinimized = true;
        localStorage.setItem('chatMinimized', 'true');
      };

      document.getElementById('openChatBtn').onclick = function() {
        document.getElementById('chatWidget').style.display = 'block';
        document.getElementById('chatWidgetMin').style.display = 'none';
        document.getElementById('chatNotifBadge').style.display = 'none';
        chatMinimized = false;
        localStorage.setItem('chatMinimized', 'false');
        setTimeout(() => { fetchMessages(); }, 5000);
      };

      setInterval(fetchMessages, 5000); // Poll for new messages every 5 seconds
fetchMessages();
    });
  }

  // --- ADMIN PANEL CHAT LOGIC ---
  if (isAdminPanel) {
    let receiver = '';
    let chatMinimized = false;
    let lastSeenMsgIds = {};
    let adminNotifInterval = null;

    // Restore lastSeenMsgIds from localStorage if present
    const storedLastSeen = localStorage.getItem('adminLastSeenMsgIds');
    if (storedLastSeen) {
      try {
        lastSeenMsgIds = JSON.parse(storedLastSeen);
      } catch (e) {
        lastSeenMsgIds = {};
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      var minimized = localStorage.getItem('chatMinimized');
      if (minimized === 'true') {
        document.getElementById('chatWidget').style.display = 'none';
        document.getElementById('chatWidgetMin').style.display = 'block';
        chatMinimized = true;
      } else {
        document.getElementById('chatWidget').style.display = 'block';
        document.getElementById('chatWidgetMin').style.display = 'none';
        chatMinimized = false;
      }
      document.getElementById('minimizeChatBtn').onclick = function() {
        document.getElementById('chatWidget').style.display = 'none';
        document.getElementById('chatWidgetMin').style.display = 'block';
        chatMinimized = true;
        localStorage.setItem('chatMinimized', 'true');
        if (adminNotifInterval) clearInterval(adminNotifInterval);
        adminNotifInterval = setInterval(pollAdminNotifications, 5000);
        console.log('ADMIN: Started polling for unread messages (minimized).');
      };
      document.getElementById('openChatBtn').onclick = function() {
        document.getElementById('chatWidget').style.display = 'block';
        document.getElementById('chatWidgetMin').style.display = 'none';
        document.getElementById('chatNotifBadge').style.display = 'none';
        chatMinimized = false;
        localStorage.setItem('chatMinimized', 'false');
        if (adminNotifInterval) clearInterval(adminNotifInterval);
        console.log('ADMIN: Stopped polling for unread messages (opened).');
        setTimeout(() => { fetchMessages(); }, 5000);
      };
      // Start polling immediately if minimized on page load
      if (minimized === 'true') {
        if (adminNotifInterval) clearInterval(adminNotifInterval);
        adminNotifInterval = setInterval(pollAdminNotifications, 5000);
        console.log('ADMIN: Started polling for unread messages (auto on load).');
      }

      
      fetchMessages();
    });

    function fetchMessages() {
      if (!receiver) { document.getElementById('chatMessages').innerHTML = '<i>Select a user to chat.</i>'; return; }
      fetch(`getMessages.php?sender=${encodeURIComponent(sender)}&receiver=${encodeURIComponent(receiver)}`)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const chat = document.getElementById('chatMessages');
            // Only scroll if user is already at (or near) the bottom
const isAtBottom = (chat.scrollHeight - chat.scrollTop - chat.clientHeight) < 20;
chat.innerHTML = data.messages.map(msg =>
  `<div style='margin-bottom:10px;text-align:${msg.sender===sender?'right':'left'};'>
    <span style='display:inline-block;padding:6px 12px;border-radius:16px;max-width:75%;background:${msg.sender===sender?'#2957A4':'#e0e0e0'};color:${msg.sender===sender?'#fff':'#222'};'>${msg.message}</span>
    <div style='font-size:11px;color:#888;'>${msg.sender} - ${msg.sent_at}</div>
  </div>`
).join('');
if (isAtBottom) chat.scrollTop = chat.scrollHeight;
            // Track last seen message for this user
            if (data.messages.length > 0) {
              lastSeenMsgIds[receiver] = data.messages[data.messages.length-1].id;
              // Persist lastSeenMsgIds in localStorage
              localStorage.setItem('adminLastSeenMsgIds', JSON.stringify(lastSeenMsgIds));
            }
            // Hide notification badge for this user
            document.getElementById('chatNotifBadge').style.display = 'none';
          }
        });
    }

    document.getElementById('sendChatBtn').onclick = function() {
      const input = document.getElementById('chatInput');
      const msg = input.value.trim();
      if (!msg || !receiver) return;
      fetch('sendMessage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `sender=${encodeURIComponent(sender)}&receiver=${encodeURIComponent(receiver)}&message=${encodeURIComponent(msg)}`
      }).then(res => res.json()).then(data => {
        if (data.success) {
          input.value = '';
          fetchMessages();
        }
      });
    };

    document.getElementById('chatInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') document.getElementById('sendChatBtn').click();
    });

    document.getElementById('chatUserSelect').addEventListener('change', function() {
      receiver = this.value;
      fetchMessages();
    });

    // Track last notified message id for each user
    let lastNotifiedMsgIds = {};

    // Poll for notifications from all users when minimized
    function pollAdminNotifications() {
      if (!chatMinimized) return;
      fetch('getAdminUnreadCounts.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'lastSeen=' + encodeURIComponent(JSON.stringify(lastSeenMsgIds))
      }).then(res => res.json()).then(data => {
        if (data.success && data.unread) {
          let hasUnread = Object.values(data.unread).some(cnt => cnt > 0);
          var notifBadge = document.getElementById('chatNotifBadge');
          var totalUnread = Object.values(data.unread).reduce((a, b) => a + b, 0);
          if (notifBadge) {
            if (totalUnread > 0) {
              notifBadge.style.display = 'block';
              notifBadge.textContent = totalUnread;
            } else {
              notifBadge.style.display = 'none';
              notifBadge.textContent = '';
            }
          }
          
          if (totalUnread > 0) console.log('ADMIN: New unread message detected!');
        }
      });
      pollAdminUnreadCounts();
    }

    // Poll unread counts for user dropdown
    function pollAdminUnreadCounts() {
      fetch('getAdminUnreadCounts.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'lastSeen=' + encodeURIComponent(JSON.stringify(lastSeenMsgIds))
      }).then(res => res.json()).then(data => {
        if (data.success && data.unread) {
          Object.keys(data.unread).forEach(user => {
            const opt = document.getElementById('userOpt_' + user);
            if (opt) {
              const cnt = data.unread[user];
              opt.textContent = cnt > 0 ? `${user} (${cnt} unread)` : user;
            }
          });
        }
      });
    }

    document.getElementById('minimizeChatBtn').onclick = function() {
      document.getElementById('chatWidget').style.display = 'none';
      document.getElementById('chatWidgetMin').style.display = 'block';
      chatMinimized = true;
      if (adminNotifInterval) clearInterval(adminNotifInterval);
      adminNotifInterval = setInterval(pollAdminNotifications, 5000);
    };

    document.getElementById('openChatBtn').onclick = function() {
      document.getElementById('chatWidget').style.display = 'block';
      document.getElementById('chatWidgetMin').style.display = 'none';
      document.getElementById('chatNotifBadge').style.display = 'none';
      chatMinimized = false;
      if (adminNotifInterval) clearInterval(adminNotifInterval);
      setTimeout(() => { fetchMessages(); }, 200);
    };

    
    fetchMessages();
    setInterval(pollAdminUnreadCounts, 5000);
    pollAdminUnreadCounts();
  }
})();
