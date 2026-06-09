const input = document.getElementById("username");
const suggestions = document.getElementById("suggestions");

let timer;

input.addEventListener("input", () => {
    clearTimeout(timer);

    timer = setTimeout(searchUsers, 300);
});

async function searchUsers() {
    const query = input.value.trim();

    if (query.length === 0) {
        suggestions.innerHTML = "";
        suggestions.style.display = "none";
        return;
    }

    try {
        const response = await fetch(
            `search_users.php?query=${encodeURIComponent(query)}`
        );

        const users = await response.json();

        suggestions.innerHTML = "";

        if (users.length === 0) {
            suggestions.style.display = "none";
            return;
        }

        users.forEach(user => {
            const item = document.createElement("div");
            item.textContent = user;

            item.addEventListener("click", () => {
                input.value = user;
                suggestions.innerHTML = "";
                suggestions.style.display = "none";
            });

            suggestions.appendChild(item);
        });

        suggestions.style.display = "block";

    } catch (error) {
        console.error("Errore nella ricerca utenti:", error);
    }
}