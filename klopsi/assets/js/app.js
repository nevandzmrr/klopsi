const nav = document.querySelector('.site-nav');
const toggle = document.querySelector('.nav-toggle');

if (toggle) {
  toggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
}

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('reveal');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.section, .spotlight-card, .gallery-slide, .date-card, .contact-panel').forEach(el => {
  el.classList.add('fade-up');
  observer.observe(el);
});

const dateCards = document.querySelectorAll('.date-card');
const dateInput = document.getElementById('reservation-date');
const stepPax = document.getElementById('step-pax');
const stepDetails = document.getElementById('step-details');
const remainingText = document.getElementById('remaining-text');
const paxInput = document.getElementById('tamu');

const updateDetailsVisibility = () => {
  if (!paxInput || !stepDetails) return;
  const remaining = Number(paxInput.max || 0);
  const value = Number(paxInput.value || 0);
  if (value > 0 && value <= remaining) {
    stepDetails.classList.remove('is-hidden');
  } else {
    stepDetails.classList.add('is-hidden');
  }
};

if (dateCards.length && dateInput && stepPax && remainingText && paxInput) {
  dateCards.forEach(card => {
    card.addEventListener('click', () => {
      if (card.disabled) return;
      dateCards.forEach(item => item.classList.remove('is-selected'));
      card.classList.add('is-selected');
      const selectedDate = card.getAttribute('data-date');
      const remaining = card.getAttribute('data-remaining');
      dateInput.value = selectedDate || '';
      remainingText.textContent = remaining || '0';
      paxInput.max = remaining || '0';
      paxInput.value = '';
      stepPax.classList.remove('is-hidden');
      stepDetails.classList.add('is-hidden');
    });
  });

  paxInput.addEventListener('input', updateDetailsVisibility);
}

const galleryTrack = document.querySelector('.gallery-track');
if (galleryTrack) {
  const slides = Array.from(galleryTrack.querySelectorAll('.gallery-slide'));
  const nextBtn = document.querySelector('.gallery-nav.next');
  const prevBtn = document.querySelector('.gallery-nav.prev');
  let index = 0;
  let autoSlide;
  let isVisible = false;

  const slideTo = (newIndex) => {
    if (!slides.length) return;
    index = (newIndex + slides.length) % slides.length;
    const slideWidth = slides[0].getBoundingClientRect().width + 18;
    galleryTrack.scrollTo({ left: slideWidth * index, behavior: 'smooth' });
  };

  const startAuto = () => {
    if (autoSlide || !isVisible) return;
    autoSlide = setInterval(() => slideTo(index + 1), 1200);
  };

  const stopAuto = () => {
    clearInterval(autoSlide);
    autoSlide = null;
  };

  if (nextBtn) nextBtn.addEventListener('click', () => slideTo(index + 1));
  if (prevBtn) prevBtn.addEventListener('click', () => slideTo(index - 1));

  galleryTrack.addEventListener('mouseenter', stopAuto);
  galleryTrack.addEventListener('mouseleave', startAuto);
  galleryTrack.addEventListener('touchstart', stopAuto, { passive: true });
  galleryTrack.addEventListener('touchend', startAuto);

  const visibilityObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      isVisible = entry.isIntersecting;
      if (isVisible) startAuto();
      else stopAuto();
    });
  }, { threshold: 0.4 });

  visibilityObserver.observe(galleryTrack);
}

const confirmForm = document.querySelector('.confirm-submit');
const modal = document.getElementById('confirm-modal');
const modalMsg = document.getElementById('confirm-message');
const modalOk = document.getElementById('confirm-ok');
const modalCancel = document.getElementById('confirm-cancel');
let pendingForm = null;

if (confirmForm && modal && modalOk && modalCancel && modalMsg) {
  confirmForm.addEventListener('submit', (event) => {
    event.preventDefault();
    pendingForm = event.currentTarget;
    modalMsg.textContent = pendingForm.getAttribute('data-confirm-text') || 'Lanjutkan menyimpan data?';
    modal.classList.add('is-open');
  });

  modalOk.addEventListener('click', () => {
    if (pendingForm) pendingForm.submit();
  });

  modalCancel.addEventListener('click', () => {
    modal.classList.remove('is-open');
    pendingForm = null;
  });
}
