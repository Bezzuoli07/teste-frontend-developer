const revealElements = document.querySelectorAll(".reveal");

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("is-visible");
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

revealElements.forEach((element) => revealObserver.observe(element));

const form = document.querySelector("#lead-form");
const feedback = document.querySelector("#form-feedback");

form?.addEventListener("submit", async (event) => {
  event.preventDefault();

  feedback.textContent = "Enviando...";
  feedback.className = "form-feedback";

  const formData = new FormData(form);

  try {
    const response = await fetch("api/submit.php", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.message || "Nao foi possivel enviar o formulario.");
    }

    feedback.textContent = result.message;
    feedback.classList.add("is-success");
    form.reset();
  } catch (error) {
    feedback.textContent = error.message;
    feedback.classList.add("is-error");
  }
});
