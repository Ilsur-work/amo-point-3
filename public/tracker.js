(function() {
  const API_URL = 'https://0eac-89-124-69-165.ngrok-free.app/api/track';
  async function track() {
    const data = {
      visitorId: localStorage.getItem('visitor_id') || (() => {
        const id = 'vis_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('visitor_id', id);
        return id;
      })(),
      device: /Mobile|Android/i.test(navigator.userAgent) ? 'Mobile' : 'Desktop',
      browser: navigator.userAgent.includes('Chrome') ? 'Chrome' :
        navigator.userAgent.includes('Firefox') ? 'Firefox' : 'Other',
      os: navigator.userAgent.includes('Windows') ? 'Windows' :
        navigator.userAgent.includes('Mac') ? 'macOS' : 'Other',
      pageUrl: window.location.href
    };

    try {
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      console.log('Visit tracked');
    } catch (error) {
      console.error('Tracking failed:', error);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', track);
  } else {
    track();
  }
})();