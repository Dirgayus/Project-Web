// Sky University KRS Management System JavaScript

document.addEventListener("DOMContentLoaded", () => {
  // Mobile menu functionality
  const mobileMenuBtn = document.getElementById("mobile-menu-btn")
  const sidebar = document.getElementById("sidebar")

  if (mobileMenuBtn && sidebar) {
    mobileMenuBtn.addEventListener("click", () => {
      sidebar.classList.toggle("open")
    })

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", (event) => {
      if (window.innerWidth <= 1024) {
        if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
          sidebar.classList.remove("open")
        }
      }
    })
  }

  // Search functionality
  const searchInput = document.querySelector(".sky-search-input")
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
      // Add search functionality here
      console.log("Searching for:", searchTerm)
    })
  }

  // Tab functionality
  const tabs = document.querySelectorAll(".sky-tab")
  tabs.forEach((tab) => {
    tab.addEventListener("click", function (e) {
      e.preventDefault()

      // Remove active class from all tabs
      tabs.forEach((t) => t.classList.remove("active"))

      // Add active class to clicked tab
      this.classList.add("active")
    })
  })

  // Card hover effects
  const cards = document.querySelectorAll(".sky-card")
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-2px)"
    })

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)"
    })
  })

  // Responsive sidebar handling
  function handleResize() {
    if (window.innerWidth > 1024) {
      sidebar.classList.remove("open")
    }
  }

  window.addEventListener("resize", handleResize)

  // Initialize tooltips (if needed)
  initializeTooltips()

  // Initialize animations
  initializeAnimations()
})

// Tooltip functionality
function initializeTooltips() {
  const tooltipElements = document.querySelectorAll("[data-tooltip]")

  tooltipElements.forEach((element) => {
    element.addEventListener("mouseenter", function () {
      const tooltipText = this.getAttribute("data-tooltip")
      const tooltip = document.createElement("div")
      tooltip.className = "tooltip"
      tooltip.textContent = tooltipText
      document.body.appendChild(tooltip)

      const rect = this.getBoundingClientRect()
      tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px"
      tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + "px"
    })

    element.addEventListener("mouseleave", () => {
      const tooltip = document.querySelector(".tooltip")
      if (tooltip) {
        tooltip.remove()
      }
    })
  })
}

// Animation functionality
function initializeAnimations() {
  const animatedElements = document.querySelectorAll(".sky-fade-in")

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1"
        entry.target.style.transform = "translateY(0)"
      }
    })
  })

  animatedElements.forEach((element) => {
    element.style.opacity = "0"
    element.style.transform = "translateY(20px)"
    element.style.transition = "opacity 0.6s ease, transform 0.6s ease"
    observer.observe(element)
  })
}

// Utility functions
function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("id-ID")
}

function formatDateTime(dateString) {
  const date = new Date(dateString)
  return date.toLocaleString("id-ID")
}

function showNotification(message, type = "info") {
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.textContent = message

  document.body.appendChild(notification)

  setTimeout(() => {
    notification.classList.add("show")
  }, 100)

  setTimeout(() => {
    notification.classList.remove("show")
    setTimeout(() => {
      notification.remove()
    }, 300)
  }, 3000)
}

// Form validation
function validateForm(formElement) {
  const requiredFields = formElement.querySelectorAll("[required]")
  let isValid = true

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      field.classList.add("error")
      isValid = false
    } else {
      field.classList.remove("error")
    }
  })

  return isValid
}

// AJAX helper function
function makeRequest(url, method = "GET", data = null) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest()
    xhr.open(method, url)
    xhr.setRequestHeader("Content-Type", "application/json")

    xhr.onload = () => {
      if (xhr.status >= 200 && xhr.status < 300) {
        try {
          const response = JSON.parse(xhr.responseText)
          resolve(response)
        } catch (e) {
          resolve(xhr.responseText)
        }
      } else {
        reject(new Error(`HTTP ${xhr.status}: ${xhr.statusText}`))
      }
    }

    xhr.onerror = () => {
      reject(new Error("Network error"))
    }

    if (data) {
      xhr.send(JSON.stringify(data))
    } else {
      xhr.send()
    }
  })
}
