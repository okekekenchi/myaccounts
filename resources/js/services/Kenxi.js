/**
 * Developed to address JS rounding limitations
 * @returns Kenxi
 */

export default function()
{
  return {

    round:(value, precision) => {
      let val = parseFloat(value) * Math.pow(10, precision),
          rounded_val = Math.round(val)
      return rounded_val / Math.pow(10, precision)
    },

    floor:(value, precision) => {
      let val = parseFloat(value) * Math.pow(10, precision),
          rounded_val = Math.floor(val)
      return rounded_val / Math.pow(10, precision)
    },

    ceil:(value, precision) => {
      let val = parseFloat(value) * Math.pow(10, precision),
          rounded_val = Math.ceil(val)
      return rounded_val / Math.pow(10, precision)
    },

  }
}