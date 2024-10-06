import { createI18n } from "vue-i18n";

async function loadMessages() {
    const context = import.meta.glob("./languages/*.json");
    const messages = {};

    for (const path in context) {
        const locale = path.match(/([a-z0-9-_]+)\.json$/i)[1];
        messages[locale] = await context[path]();
    }

    return { messages };
}

const { messages } = await loadMessages();

const i18n = createI18n({
    legacy: false,
    locale: "en",
    fallbackLocale: "en",
    messages: messages
});

export default i18n;
