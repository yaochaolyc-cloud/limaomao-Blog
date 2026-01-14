document.addEventListener('DOMContentLoaded', function () {
    const mode = Limaomao.mode || 'auto';
    const html = document.documentElement;

    function setDarkMode(isDark) {
        if (isDark) {
            html.classList.add('dark-mode');
            localStorage.setItem('color-mode', 'dark');
        } else {
            html.classList.remove('dark-mode');
            localStorage.setItem('color-mode', 'light');
        }
    }

    if (mode === 'dark') {
        setDarkMode(true);
    } else if (mode === 'light') {
        setDarkMode(false);
    } else {
        const saved = localStorage.getItem('color-mode');
        if (saved === 'dark' || saved === 'light') {
            setDarkMode(saved === 'dark');
        } else {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            setDarkMode(isDark);
        }
    }

    // 搜索框切换
    const toggle = document.getElementById('search-toggle');
    const box = document.getElementById('search-box');
    if (toggle && box) {
        toggle.addEventListener('click', () => {
            box.classList.toggle('active');
        });
    }

    // 回到顶部
    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
