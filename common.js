// Ühised andmed: maalid (kataloog) ja saali raamid. Sama info on api.php-s.
export const PAINTINGS = {
  portree:     { img: 'img/portree.jpg',     title: 'Naise portree',             author: 'Julie Wilhelmine Hagen-Schwarz', year: '19. sajand',   g: 'portree' },
  monet:       { img: 'img/monet.jpg',       title: 'Impressioon. Tõusev päike', author: 'Claude Monet',                   year: '1872',         g: 'maastik' },
  caillebotte: { img: 'img/caillebotte.jpg', title: 'Puuviljad turuletil',        author: 'Gustave Caillebotte',            year: 'u 1881–1882',  g: 'natyyrmort' },
  paevalilled: { img: 'img/paevalilled.jpg', title: 'Päevalilled',               author: 'Vincent van Gogh',               year: '1888',         g: 'natyyrmort' }
};
export const FRAMES = {
  F1: { label: 'PORTREED', g: 'portree' },
  F2: { label: 'MAASTIKUD', g: 'maastik' },
  F3: { label: 'NATÜÜRMORDID', g: 'natyyrmort' }
};
export const GENRE = { portree: 'portree', maastik: 'maastik', natyyrmort: 'natüürmort' };

export function getRoom() {
  const r = new URLSearchParams(location.search).get('room') || '';
  return r.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 10);
}
export async function api(room, who, body) {
  const url = 'api.php?room=' + encodeURIComponent(room) + '&who=' + who + '&t=' + Date.now();
  const opt = body ? { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) } : { cache: 'no-store' };
  const r = await fetch(url, opt);
  if (!r.ok) throw new Error('HTTP ' + r.status);
  return r.json();
}
