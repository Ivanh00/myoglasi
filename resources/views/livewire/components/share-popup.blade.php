{{-- Share Popup Component --}}
<script>
    function shareItem(title, text, url) {
        // Check if mobile and has native share
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        if (navigator.share && isMobile) {
            // Use native share on mobile
            navigator.share({
                title: title,
                text: text,
                url: url
            }).catch(err => {
                // If share fails, show popup
                showSharePopup(url, title, text);
            });
        } else {
            // Desktop - show popup
            showSharePopup(url, title, text);
        }
    }

    function showSharePopup(url, title, text) {
        // Remove existing popup if any
        const existing = document.getElementById('sharePopup');
        if (existing) existing.remove();

        // Check if mobile
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        // Create popup container
        const overlay = document.createElement('div');
        overlay.id = 'sharePopup';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

        // Create popup content
        const popup = document.createElement('div');
        popup.className = 'bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4';

        // Header
        const header = document.createElement('div');
        header.className = 'flex justify-between items-center mb-4';

        const heading = document.createElement('h3');
        heading.className = 'text-lg font-semibold text-slate-800 dark:text-slate-200';
        heading.textContent = 'Podeli';

        const closeBtn = document.createElement('button');
        closeBtn.className = 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.onclick = () => overlay.remove();

        header.appendChild(heading);
        header.appendChild(closeBtn);

        // Share options container
        const options = document.createElement('div');
        options.className = 'space-y-3';

        // Facebook
        const fbLink = document.createElement('a');
        fbLink.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
        fbLink.target = '_blank';
        fbLink.className = 'flex items-center p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors';
        fbLink.innerHTML = '<i class="fab fa-facebook-f w-6"></i><span class="ml-3">Podeli na Facebook-u</span>';

        // WhatsApp
        const waLink = document.createElement('a');
        waLink.href = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
        waLink.target = '_blank';
        waLink.className = 'flex items-center p-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors';
        waLink.innerHTML = '<i class="fab fa-whatsapp w-6"></i><span class="ml-3">Podeli na WhatsApp-u</span>';

        // Viber (mobile only)
        if (isMobile) {
            const viberLink = document.createElement('a');
            viberLink.href = 'viber://forward?text=' + encodeURIComponent(text + ' ' + url);
            viberLink.className = 'flex items-center p-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors';
            viberLink.innerHTML = '<i class="fab fa-viber w-6"></i><span class="ml-3">Podeli na Viber-u</span>';
            options.appendChild(viberLink);
        }

        // Email
        const emailLink = document.createElement('a');
        emailLink.href = 'mailto:?subject=' + encodeURIComponent(title) + '&body=' + encodeURIComponent(text + '\n\n' + url);
        emailLink.className = 'flex items-center p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors';
        emailLink.innerHTML = '<i class="fas fa-envelope w-6"></i><span class="ml-3">Pošalji Email</span>';

        // Copy link button
        const copyBtn = document.createElement('button');
        copyBtn.className = 'w-full flex items-center p-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors';
        copyBtn.innerHTML = '<i class="fas fa-link w-6"></i><span class="ml-3">Kopiraj link</span>';
        copyBtn.onclick = () => {
            copyToClipboard(url);
            overlay.remove();
        };

        // Add all options
        options.appendChild(fbLink);
        options.appendChild(waLink);
        options.appendChild(emailLink);
        options.appendChild(copyBtn);

        // Assemble popup
        popup.appendChild(header);
        popup.appendChild(options);
        overlay.appendChild(popup);

        // Close on overlay click
        overlay.onclick = (e) => {
            if (e.target === overlay) overlay.remove();
        };

        document.body.appendChild(overlay);
    }

    function copyToClipboard(url) {
        navigator.clipboard.writeText(url).then(() => {
            // Show success message
            const message = document.createElement('div');
            message.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';

            const icon = document.createElement('i');
            icon.className = 'fas fa-check mr-2';

            const text = document.createElement('span');
            text.textContent = 'Link je kopiran u clipboard!';

            message.appendChild(icon);
            message.appendChild(text);
            document.body.appendChild(message);

            setTimeout(() => {
                message.remove();
            }, 3000);
        }).catch(err => {
            alert('Greška pri kopiranju linka');
        });
    }
</script>
