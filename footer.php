    </main>

    <div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()" aria-label="Close dialog">&times;</span>
            <h2 id="modalTitle">🎓 Notification</h2>
            <p id="modalText">Message goes here...</p>
            <br>
            <button type="button" onclick="closeModal()">Close</button>
        </div>
    </div>

    <button class="theme-btn" id="themeBtn" aria-label="Toggle dark mode theme">🌙</button>
    <button id="scrollTopBtn" onclick="scrollTopPage()" aria-label="Scroll back to top of page">⬆</button>

    <footer role="contentinfo">
        <p>© 2026 Student Hub Portal | ITUE203 Full-Stack Web Development Frameworks | All Rights Reserved</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
