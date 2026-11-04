(function () {
	// SPメニュー（header.php の data-menu-toggle / data-menu-drawer）。Esc で閉じ、閉じたらボタンへフォーカスを戻す
	var toggle = document.querySelector('[data-menu-toggle]');
	var drawer = document.querySelector('[data-menu-drawer]');

	function setMenu(open) {
		document.body.classList.toggle('is-menu-open', open);
		drawer.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
		if (open) {
			drawer.removeAttribute('inert');
			var first = drawer.querySelector('a');
			if (first) { first.focus(); }
		} else {
			drawer.setAttribute('inert', '');
		}
	}

	if (toggle && drawer) {
		toggle.addEventListener('click', function () {
			setMenu(toggle.getAttribute('aria-expanded') !== 'true');
		});
		drawer.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () { setMenu(false); });
		});
		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				setMenu(false);
				toggle.focus();
			}
		});
	}

	document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
		anchor.addEventListener('click', function (event) {
			var target = document.querySelector(anchor.getAttribute('href'));
			if (!target) {
				return;
			}
			event.preventDefault();
			target.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	});

	// 動画モーダル: data-video-url を持つ要素のクリックで YouTube をモーダル再生
	function youtubeEmbedUrl(url) {
		var m = url.match(/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|shorts\/|embed\/|live\/)|youtu\.be\/)([\w-]{6,})/);
		return m ? 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1&rel=0' : null;
	}

	function closeVideoModal() {
		var modal = document.querySelector('.video-modal');
		if (modal) {
			modal.remove();
			document.body.classList.remove('is-video-open');
		}
	}

	function openVideoModal(url) {
		var embed = youtubeEmbedUrl(url);
		if (!embed) {
			window.open(url, '_blank', 'noopener');
			return;
		}
		closeVideoModal();
		var modal = document.createElement('div');
		modal.className = 'video-modal';
		modal.innerHTML =
			'<div class="video-modal__backdrop"></div>' +
			'<div class="video-modal__dialog" role="dialog" aria-modal="true" aria-label="動画">' +
			'<button type="button" class="video-modal__close" aria-label="閉じる">×</button>' +
			'<div class="video-modal__frame"><iframe src="' + embed + '" title="動画" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe></div>' +
			'</div>';
		modal.querySelector('.video-modal__backdrop').addEventListener('click', closeVideoModal);
		modal.querySelector('.video-modal__close').addEventListener('click', closeVideoModal);
		document.body.appendChild(modal);
		document.body.classList.add('is-video-open');
		modal.querySelector('.video-modal__close').focus();
	}

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeVideoModal();
		}
	});

	document.querySelectorAll('[data-video-url]').forEach(function (el) {
		el.addEventListener('click', function (event) {
			event.preventDefault();
			openVideoModal(el.getAttribute('data-video-url'));
		});
	});

	document.querySelectorAll('.reason-slider').forEach(function (slider) {
		var track = slider.querySelector('.reason-slider__track');
		var slides = Array.prototype.slice.call(slider.querySelectorAll('.reason-slide'));
		var prev = slider.querySelector('.reason-slider__arrow--prev');
		var next = slider.querySelector('.reason-slider__arrow--next');
		var dots = slider.querySelector('.reason-slider__dots');

		if (!track || !slides.length || !prev || !next || !dots) {
			return;
		}

		slides.forEach(function (_, index) {
			var dot = document.createElement('button');
			dot.type = 'button';
			dot.className = 'reason-slider__dot';
			dot.setAttribute('aria-label', (index + 1) + '枚目へ');
			dot.addEventListener('click', function () {
				slides[index].scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
			});
			dots.appendChild(dot);
		});

		var dotItems = Array.prototype.slice.call(dots.querySelectorAll('.reason-slider__dot'));

		function currentIndex() {
			var trackLeft = track.getBoundingClientRect().left;
			var closest = 0;
			var closestDistance = Infinity;

			slides.forEach(function (slide, index) {
				var distance = Math.abs(slide.getBoundingClientRect().left - trackLeft);
				if (distance < closestDistance) {
					closestDistance = distance;
					closest = index;
				}
			});

			return closest;
		}

		function updateDots() {
			var active = currentIndex();
			dotItems.forEach(function (dot, index) {
				dot.classList.toggle('is-active', index === active);
			});
		}

		function go(direction) {
			var index = currentIndex();
			var nextIndex = Math.max(0, Math.min(slides.length - 1, index + direction));
			slides[nextIndex].scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
		}

		prev.addEventListener('click', function () { go(-1); });
		next.addEventListener('click', function () { go(1); });
		track.addEventListener('scroll', function () {
			window.requestAnimationFrame(updateDots);
		}, { passive: true });
		window.addEventListener('resize', updateDots);
		updateDots();
	});
})();

// LINEクリック計測（content/サイト設計.md §5-3）: GA4 に line_click を送る。
// page_type は body の pt-<種別>、link_location はリンクの data-cta（なければ other）
(function () {
	var pt = (document.body.className.match(/(?:^|\s)pt-([\w-]+)/) || [])[1] || 'other';
	document.addEventListener('click', function (event) {
		// LINE は line_click、Web予約フォームへのリンクは trial_form_click（どちらも位置別）
		var link = event.target.closest && event.target.closest('a[href*="line.me"], a[href*="/contact/"]');
		if (!link) {
			return;
		}
		var name = link.href.indexOf('line.me') !== -1 ? 'line_click' : 'trial_form_click';
		var params = { page_type: pt, link_location: link.getAttribute('data-cta') || 'other' };
		if (typeof window.gtag === 'function') {
			window.gtag('event', name, params);
		} else {
			window.dataLayer = window.dataLayer || [];
			window.dataLayer.push(Object.assign({ event: name }, params));
		}
	});
})();

// 体験・見学予約フォーム（/contact/）
// - 日曜・毎月15日・過去日をその場で弾く（サーバー側でも同じ検証あり）
// - 第2・第3希望は任意。日付か時間の片方だけなら両方を求める
// - 送信ボタンを押したら無効化して二重送信を防ぐ
// - 受付成功（?sent=）で trial_form_submit を1回だけ送る
(function () {
	var done = document.querySelector('.form-success[data-submit-token]');
	if (done) {
		var key = 'geed_submit_' + done.getAttribute('data-submit-token');
		var seen = false;
		try { seen = !!sessionStorage.getItem(key); sessionStorage.setItem(key, '1'); } catch (e) {}
		if (!seen) {
			var params = { page_type: 'contact', form: 'trial' };
			if (typeof window.gtag === 'function') {
				window.gtag('event', 'trial_form_submit', params);
			} else {
				window.dataLayer = window.dataLayer || [];
				window.dataLayer.push(Object.assign({ event: 'trial_form_submit' }, params));
			}
		}
	}

	var form = document.querySelector('.contact-form');
	if (!form) {
		return;
	}

	function dateMessage(value) {
		if (!value) {
			return '';
		}
		var p = value.split('-');
		var d = new Date(+p[0], +p[1] - 1, +p[2]);
		var today = new Date();
		today.setHours(0, 0, 0, 0);
		if (d < today) {
			return '過去の日付は選べません。';
		}
		if (d.getDay() === 0) {
			return '日曜日はノースタッフ営業のためご予約を承れません。';
		}
		if (d.getDate() === 15) {
			return '毎月15日は休館日です。';
		}
		return '';
	}

	function checkSlot(i) {
		var date = form.querySelector('#cf-date' + i);
		var time = form.querySelector('#cf-time' + i);
		if (!date || !time) {
			return;
		}
		var msg = dateMessage(date.value);
		if (!msg && i > 1 && !date.value && time.value) {
			msg = '日付も選択してください。';
		}
		date.setCustomValidity(msg);
		time.setCustomValidity(i > 1 && date.value && !time.value ? '時間も選択してください。' : '');
	}

	[1, 2, 3].forEach(function (i) {
		['#cf-date' + i, '#cf-time' + i].forEach(function (sel) {
			var el = form.querySelector(sel);
			if (el) {
				el.addEventListener('change', function () {
					checkSlot(i);
					if (el.type === 'date') {
						el.reportValidity();
					}
				});
			}
		});
		checkSlot(i);
	});

	form.addEventListener('submit', function (event) {
		[1, 2, 3].forEach(checkSlot);
		if (!form.checkValidity()) {
			event.preventDefault();
			form.reportValidity();
			return;
		}
		var btn = form.querySelector('button[type="submit"]');
		if (btn) {
			if (btn.disabled) {
				event.preventDefault();
				return;
			}
			btn.disabled = true;
			btn.textContent = btn.getAttribute('data-sending-label') || btn.textContent;
		}
	});

	// 戻るボタンでキャッシュから表示されたときにボタンを戻す
	window.addEventListener('pageshow', function (event) {
		var btn = form.querySelector('button[type="submit"]');
		if (event.persisted && btn) {
			btn.disabled = false;
			btn.textContent = '予約フォームを送信する';
		}
	});
})();
