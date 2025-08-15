import './bootstrap';

// Ajax submit for comment forms (delegated)
document.addEventListener('submit', async function (e) {
	const form = e.target;
	if (!form.closest || !form.closest('.comments-section')) return;
	if (!form.matches || !form.matches('.comments-section form')) return;

	e.preventDefault();

	const url = form.action;
	const formData = new FormData(form);
	const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

	try {
		const res = await fetch(url, {
			method: form.method || 'POST',
			headers: {
				'Accept': 'application/json',
				'X-Requested-With': 'XMLHttpRequest',
				'X-CSRF-TOKEN': token || ''
			},
			body: formData
		});

		if (!res.ok) throw new Error('Network response was not ok');
		const data = await res.json();
		if (data.success && data.html) {
			// replace the nearest comments-section with returned HTML
			const wrapper = form.closest('.comments-section');
			if (wrapper) {
				const parent = wrapper.parentNode;
				// parse the returned HTML and extract the root node
				const tmp = document.createElement('div');
				tmp.innerHTML = data.html;
				const newSection = tmp.querySelector('.comments-section');
				if (newSection) parent.replaceChild(newSection, wrapper);
			}
		}
	} catch (err) {
		console.error('Failed to submit comment', err);
		// fall back to normal submit
		form.removeEventListener('submit', () => {});
		form.submit();
	}
});
