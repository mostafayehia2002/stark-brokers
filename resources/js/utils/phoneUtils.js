/**
 * Detects the country code from a phone number
 * Returns 'SA' for Saudi, 'EG' for Egypt, or null if unknown
 */
export const detectCountryCode = (phone) => {
  let cleaned = phone.replace(/\D/g, '')

  if (cleaned.startsWith('966') || cleaned.startsWith('0966')) {
    return 'SA'
  }
  if (cleaned.startsWith('20') || cleaned.startsWith('020')) {
    return 'EG'
  }
  if (cleaned.startsWith('5')) {
    return 'SA' // Saudi mobile starting with 5
  }
  if (cleaned.match(/^1[0-2]/)) {
    return 'EG' // Egyptian mobile starting with 10-12
  }

  return null
}

/**
 * Formats a phone number to the standard E.164 format
 * Supports both Saudi (+966) and Egyptian (+20) numbers
 * Accepts formats:
 * Saudi:
 *   - 0550427014, 550427014, +966550427014, 966550427014
 * Egyptian:
 *   - 01001234567, 1001234567, +201001234567, 201001234567
 * Returns: +966XXXXXXXXX or +20XXXXXXXXX (with + prefix)
 */
export const formatPhoneNumber = (phone) => {
  // Remove all non-digit characters
  let cleaned = phone.replace(/\D/g, '')

  // Remove leading zeros
  cleaned = cleaned.replace(/^0+/, '')

  const country = detectCountryCode(cleaned)

  if (country === 'SA') {
    if (cleaned.startsWith('966')) {
      cleaned = cleaned.substring(3)
    }
    return `+966${cleaned}`
  }

  if (country === 'EG') {
    if (cleaned.startsWith('20')) {
      cleaned = cleaned.substring(2)
    }
    return `+20${cleaned}`
  }

  // Do not guess a country for ambiguous numbers
  return cleaned
}

/**
 * Validates if a phone number is a valid Saudi mobile number
 * Should match +966 followed by 9 digits (starting with 5)
 */
export const validateSaudiPhone = (phone) => {
  const saudiPhoneRegex = /^\+9665\d{8}$/
  return saudiPhoneRegex.test(phone)
}

/**
 * Validates if a phone number is a valid Egyptian mobile number
 * Should match +20 followed by 10 digits (starting with 10, 11, or 12)
 */
export const validateEgyptianPhone = (phone) => {
  const egyptianPhoneRegex = /^\+201[0-2]\d{8}$/
  return egyptianPhoneRegex.test(phone)
}

/**
 * Validates if a phone number is valid (either Saudi or Egyptian)
 */
export const isValidPhoneNumber = (phone) => {
  return validateSaudiPhone(phone) || validateEgyptianPhone(phone)
}

/**
 * Formats a phone number for display in the UI
 * Input: any valid format
 * Output: +966 5X XXX XXXX (for Saudi) or +20 1XX XXX XXXX (for Egyptian)
 */
export const formatPhoneForDisplay = (phone) => {
  try {
    // First normalize the number
    const normalized = formatPhoneNumber(phone)

    // Format based on country code
    if (normalized.startsWith('+966')) {
      const cleaned = normalized.replace(/^\+966/, '')
      return `+966 ${cleaned.slice(0, 1)} ${cleaned.slice(1, 4)} ${cleaned.slice(4)}`
    }

    if (normalized.startsWith('+20')) {
      const cleaned = normalized.replace(/^\+20/, '')
      return `+20 ${cleaned.slice(0, 2)} ${cleaned.slice(2, 5)} ${cleaned.slice(5)}`
    }

    return normalized // Return normalized if no match
  } catch (error) {
    return phone // Return original if formatting fails
  }
}

/**
 * Normalizes any valid Saudi phone format to API format
 * Input: any valid format
 * Output: +966XXXXXXXXX (with + prefix)
 */
export const normalizePhone = (phone) => {
  return formatPhoneNumber(phone)
}

/**
 * Validates if a string is a valid OTP code
 */
export const validateOTP = (otp) => {
  return /^\d{6}$/.test(otp)
}
