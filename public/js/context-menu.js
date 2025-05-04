document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-context-menu]").forEach((element) => {
        element.addEventListener("contextmenu", function (e) {
            e.preventDefault();

            // Remove any existing context menu
            document.querySelectorAll(".custom-context-menu").forEach(menu => menu.remove());

            // Create a new context menu
            const menu = document.createElement("div");
            menu.classList.add("custom-context-menu");
            menu.innerHTML = `
                <div class="bg-white border border-gray-200 shadow-md rounded-md w-40 z-50">
                    <button data-action="edit" class="block w-full px-4 py-2 hover:bg-gray-100">Edit</button>
                    <button data-action="delete" class="block w-full px-4 py-2 hover:bg-red-100 text-red-500">Delete</button>
                    <button data-action="followup" class="block w-full px-4 py-2 hover:bg-gray-100">Add Follow-up</button>
                </div>
            `;

            document.body.appendChild(menu);

            // Position the menu
            menu.style.position = "absolute";
            menu.style.top = `${e.clientY}px`;
            menu.style.left = `${e.clientX}px`;

            // Add click actions
            menu.addEventListener("click", (event) => {
                const action = event.target.getAttribute("data-action");

                if (action === "edit") {
                    Livewire.emit('editRecord', element.dataset.id);
                } else if (action === "delete") {
                    Livewire.emit('deleteRecord', element.dataset.id);
                } else if (action === "followup") {
                    Livewire.emit('addFollowUp', element.dataset.id);
                }

                menu.remove();
            });

            // Hide menu on click outside
            document.addEventListener("click", () => menu.remove(), { once: true });
        });
    });
});
