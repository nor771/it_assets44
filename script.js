document.addEventListener('DOMContentLoaded', function() {
    const ITAssets = {
        init: function() {
            this.initDeleteLinks();
            this.initSupportChat();
            this.initQuickSolutions();
            this.initReportsModal();
            this.initAiChat();
        },
        
        initDeleteLinks: function() {
            const deleteLinks = document.querySelectorAll('.delete-link');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const isConfirmed = confirm('هل أنت متأكد أنك تريد حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.');
                    if (isConfirmed) {
                        window.location.href = this.getAttribute('href');
                    }
                });
            });
        },
        
        initSupportChat: function() {
            const supportTrigger = document.getElementById('support-trigger');
            const supportChatModal = document.getElementById('support-chat-modal');
            const closeSupportModal = document.getElementById('close-support-modal');
            const supportChatBody = document.querySelector('.support-chat-body');
            const supportChatInput = document.querySelector('.support-chat-footer input');
            const supportChatForm = document.querySelector('.support-chat-footer form');
            
            if (supportTrigger) {
                supportTrigger.addEventListener('click', function() {
                    supportChatModal.style.display = 'flex';
                    if (supportChatBody.children.length === 0) {
                        ITAssets.addSupportMessage('مرحباً بك! أنا مساعد الدعم الفني. كيف يمكنني مساعدتك اليوم؟', 'bot');
                    }
                });
            }
            
            if (closeSupportModal) {
                closeSupportModal.addEventListener('click', function() {
                    supportChatModal.style.display = 'none';
                });
            }
            
            window.addEventListener('click', function(event) {
                if (event.target == supportChatModal) {
                    supportChatModal.style.display = 'none';
                }
            });
            
            if (supportChatForm) {
                supportChatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const userMessage = supportChatInput.value.trim();
                    if (userMessage !== '') {
                        ITAssets.addSupportMessage(userMessage, 'user');
                        const chatSentSound = new Audio('sounds/chat-sent.mp3');
                        chatSentSound.play().catch(error => console.error("Sound play failed:", error));
                        
                        setTimeout(() => {
                            const botResponse = ITAssets.generateBotResponse(userMessage);
                            ITAssets.addSupportMessage(botResponse, 'bot');
                        }, 1000);
                        
                        supportChatInput.value = '';
                    }
                });
            }
        },
        
        addSupportMessage: function(message, sender) {
            const supportChatBody = document.querySelector('.support-chat-body');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('support-message', sender);
            messageDiv.textContent = message;
            supportChatBody.appendChild(messageDiv);
            supportChatBody.scrollTop = supportChatBody.scrollHeight;
        },
        
        generateBotResponse: function(userMessage) {
            const responses = {
                'طابعة': 'لحل مشكلة الطابعة، يرجى التحقق من: 1. تشغيل الطابعة وتوصيل الكهرباء. 2. كابلات USB أو الشبكة. 3. إعادة تشغيل الطابعة والكمبيوتر.',
                'انترنت': 'لحل مشكلة الإنترنت، يرجى: 1. إعادة تشغيل الراوتر. 2. التحقق من كابلات الشبكة. 3. التواصل مع مزود الخدمة.',
                'جهاز': 'لحل مشكلة الجهاز البطيء، يرجى: 1. إغلاق البرامج غير المستخدمة. 2. إلغاء تثبيت البرامج غير الضرورية. 3. فحص الجهاز بحثًا عن فيروسات.',
                'كلمة مرور': 'لحل مشكلة نسيان كلمة المرور، يرجى: 1. استخدام خيار "هل نسيت كلمة المرور؟". 2. التواصل مع مسؤول النظام لإعادة تعيينها.',
                'شاشة زرقاء': 'لحل مشكلة الشاشة الزرقاء، يرجى: 1. إعادة تشغيل الجهاز. 2. الدخول إلى "الوضع الآمن" وإلغاء تثبيت آخر تحديث. 3. استعادة النظام.'
            };
            
            for (const key in responses) {
                if (userMessage.toLowerCase().includes(key)) {
                    return responses[key];
                }
            }
            
            return 'تم استلام رسالتك، سنقوم بالرد عليك قريباً.';
        },
        
        initQuickSolutions: function() {
            const searchInput = document.getElementById('solutions-search-input');
            const suggestionsList = document.getElementById('suggestions-list');
            
            const solutions = [
                { problem: 'طابعة لا تعمل', solution: '1. تأكد من تشغيل الطابعة وتوصيل الكهرباء. 2. تحقق من كابلات USB أو الشبكة. 3. أعد تشغيل الطابعة والكمبيوتر.' },
                { problem: 'انقطاع الإنترنت', solution: '1. أعد تشغيل الراوتر. 2. تحقق من كابلات الشبكة. 3. تواصل مع مزود الخدمة.' },
                { problem: 'الجهاز بطيء جداً', solution: '1. أغلق البرامج غير المستخدمة. 2. قم بإلغاء تثبيت البرامج غير الضرورية. 3. فحص الجهاز بحثًا عن فيروسات.' },
                { problem: 'نسيت كلمة المرور', solution: '1. استخدم خيار "هل نسيت كلمة المرور؟" في شاشة تسجيل الدخول. 2. تواصل مع مسؤول النظام لإعادة تعيينها.' },
                { problem: 'الشاشة زرقاء', solution: '1. أعد تشغيل الجهاز. 2. أدخل إلى "الوضع الآمن" وإلغاء تثبيت آخر تحديث. 3. استعادة النظام.' }
            ];
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    suggestionsList.innerHTML = '';
                    if (query.length > 0) {
                        const filteredSolutions = solutions.filter(s => s.problem.toLowerCase().includes(query));
                        if (filteredSolutions.length > 0) {
                            suggestionsList.style.display = 'block';
                            filteredSolutions.forEach(s => {
                                const item = document.createElement('div');
                                item.className = 'suggestion-item';
                                item.innerHTML = `<strong>${s.problem}</strong><br><small>${s.solution}</small>`;
                                suggestionsList.appendChild(item);
                            });
                        } else {
                            suggestionsList.style.display = 'none';
                        }
                    } else {
                        suggestionsList.style.display = 'none';
                    }
                });
            }
        },
        
        initReportsModal: function() {
            const reportsModal = document.getElementById('reports-modal');
            const reportsCard = document.getElementById('reports-card');
            const closeReportsModal = document.getElementById('close-reports-modal');
            const printReportBtn = document.getElementById('print-report-btn');
            
            function openReportsModal() {
                reportsModal.style.display = 'block';
                ITAssets.populateReportsTable();
            }
            
            function closeReportsModalFunc() {
                reportsModal.style.display = 'none';
            }
            
            if (reportsCard) reportsCard.addEventListener('click', openReportsModal);
            if (closeReportsModal) closeReportsModal.addEventListener('click', closeReportsModalFunc);
            
            window.addEventListener('click', function(event) {
                if (event.target == reportsModal) closeReportsModalFunc();
            });
            
            if (printReportBtn) printReportBtn.addEventListener('click', function() {
                window.print();
            });
        },
        
        populateReportsTable: function() {
            const computersTbody = document.getElementById('computers-report-tbody');
            const printersTbody = document.getElementById('printers-report-tbody');
            const landlinesTbody = document.getElementById('landlines-report-tbody');
            
            if (computersTbody) {
                computersTbody.innerHTML = '';
                reportData.computers.forEach(item => {
                    computersTbody.innerHTML += `<tr><td>${item.id || item.asset_tag}</td><td>${item.model}</td><td>${item.status}</td></tr>`;
                });
            }
            
            if (printersTbody) {
                printersTbody.innerHTML = '';
                reportData.printers.forEach(item => {
                    printersTbody.innerHTML += `<tr><td>${item.id || item.asset_tag}</td><td>${item.model}</td><td>${item.status}</td><td>${item.location}</td></tr>`;
                });
            }
            
            if (landlinesTbody) {
                landlinesTbody.innerHTML = '';
                reportData.landlines.forEach(item => {
                    landlinesTbody.innerHTML += `<tr><td>${item.phone_number}</td><td>${item.department}</td><td>${item.status}</td><td>${item.location}</td></tr>`;
                });
            }
        },
        
        initAiChat: function() {
            const aiCard = document.getElementById('ai-chat-card');
            if (aiCard) {
                aiCard.addEventListener('click', function() {
                    alert('techo.techo: مساعدك الذكي جاهز للمساعدة! (هذه ميزة تجريبية)');
                });
            }
        }
    };

    ITAssets.init();
    
    const pendingTasksCard = document.getElementById('pending-tasks-card');
    if (pendingTasksCard) {
        const notificationSound = new Audio('sounds/notification.mp3');
        notificationSound.play().catch(e => console.error("Sound play failed:", e));
        const tasksIcon = pendingTasksCard.querySelector('i');
        if (tasksIcon) {
            tasksIcon.classList.add('pulse-animation');
        }
    }
});

window.viewAsset = (id) => {
    const asset = assets.find(a => a.id === id);
    if (asset) {
        document.getElementById('report-id').textContent = asset.id;
        document.getElementById('report-name').textContent = asset.name;
        document.getElementById('report-type').textContent = getTypeLabel(asset.type);
        document.getElementById('report-status').textContent = getStatusLabel(asset.status);
        document.getElementById('report-user').textContent = asset.user;
        
        const today = new Date().toLocaleDateString('ar-EG');
        document.getElementById('report-date').textContent = today;

        reportModal.style.display = 'block';
    }
};