const API = './api';
const listingsRoot = document.getElementById('listings');
const btnSearch = document.getElementById('btn-search');
const cityInput = document.getElementById('city');
const ptypeInput = document.getElementById('ptype');
const addListingForm = document.querySelector('.add-listing-form');
const btnAdd = document.getElementById('btn-add');
const addListingSection = document.getElementById('add-listing-section');

async function fetchListings(page = 1) {
  listingsRoot.innerHTML = '<p>Loading...</p>';
  const res = await fetch(`${API}/listings.php?page=${page}`);
  if (!res.ok) {
    listingsRoot.innerHTML = '<p>Error loading listings</p>';
    return;
  }
  const data = await res.json();
  renderListings(data.data);
}

function renderListings(items) {
  if (!items || items.length === 0) {
    listingsRoot.innerHTML = '<p>No listings found.</p>';
    return;
  }
  listingsRoot.innerHTML = items.map(item => `
    <article class="card">
      <a href="property.html?id=${item.id}">
        <img src="${item.image_url || 'https://via.placeholder.com/600x350?text=EstateEase'}" alt="${escapeHtml(item.title)}">
        <div class="card-content">
          <h3>${escapeHtml(item.title)}</h3>
          <p class="meta">${escapeHtml(item.city)} • ${escapeHtml(item.property_type)}</p>
          <div class="price">₹${Number(item.price).toLocaleString('en-IN')}</div>
          <span class="view-details-link">View details →</span>
        </div>
      </a>
    </article>
  `).join('');
}

btnSearch.addEventListener('click', async () => {
  const city = cityInput.value.trim();
  const ptype = ptypeInput.value;

  const params = new URLSearchParams();
  if (city) params.set('city', city);
  if (ptype) params.set('property_type', ptype);

  const res = await fetch(`${API}/listings.php?${params.toString()}`);
  const json = await res.json();
  renderListings(json.data);
});

function escapeHtml(str) {
  if (!str) return '';
  return str.replace(/[&<>"']/g, s => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
  }[s]));
}

btnAdd.addEventListener('click', () => {
  addListingSection.scrollIntoView({ behavior: 'smooth' });
});

addListingForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const listingData = {
    title: document.getElementById('title').value,
    description: document.getElementById('description').value,
    price: document.getElementById('price').value,
    city: document.getElementById('add-city').value,
    property_type: document.getElementById('property_type').value,
    bedrooms: document.getElementById('bedrooms').value,
    bathrooms: document.getElementById('bathrooms').value,
    area_sq_m: document.getElementById('area_sq_m').value,
    image_url: document.getElementById('image_url').value
  };

  const res = await fetch(`${API}/add_listing.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(listingData)
  });
  const data = await res.json();
  
  if (res.ok) {
    alert('Listing added successfully!');
    addListingForm.reset();
    fetchListings();
  } else {
    alert('Error: ' + data.error);
  }
});

fetchListings();