export function formatDate(
    isoString,
    locale = 'en-US',
    options = { dateStyle: 'medium', timeStyle: 'short' }
) {
    if (!isoString) return '';
    const d = new Date(isoString);
    return isNaN(d) ? '' : d.toLocaleString(locale, options);
}
