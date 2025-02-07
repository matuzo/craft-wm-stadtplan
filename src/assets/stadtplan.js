import '/wienermelange/assets/js/components/Map/Map.js';
import '/wienermelange/assets/js/components/Icon/Icon.js';
import '/wienermelange/assets/js/components/Input/Input.js';
import '/wienermelange/assets/js/components/Stack/Stack.js';

document.querySelector('#content').addEventListener("wm-map-marker-submit", e => {
  const map = e.target
  const wrapper = map.closest('[data-id]')

  // Set lat and long
  const lng = wrapper.querySelector("input[id*=\"fields-addressLong\"]")
  const lat = wrapper.querySelector("input[id*=\"fields-addressLat\"]")

  lng.value = e.detail.lng
  lat.value = e.detail.lat

  if (e.detail.address) {
    const street = wrapper.querySelector("input[id*=\"fields-addressStreet\"]");
    const number = wrapper.querySelector("input[id*=\"fields-addressNumber\"]");
    const address = e.detail.address
    street.value = address;

      if (address.split(" ").length > 1) {
        const addressArray = address.split(" ");
        street.value = addressArray.slice(0, -1).join(" ")
        number.value = addressArray[addressArray.length - 1]
      } else {
          number.value = ""
      }
  } else {
      street.value = ""
  }
})

