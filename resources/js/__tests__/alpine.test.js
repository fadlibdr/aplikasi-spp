import { describe, it, expect, vi } from 'vitest'

const startMock = vi.fn()
vi.mock('alpinejs', () => ({ default: { start: startMock } }))

import '../app.js'

describe('Alpine initialization', () => {
  it('adds Alpine to window and calls start', () => {
    expect(window.Alpine).toBeDefined()
    expect(startMock).toHaveBeenCalled()
  })
})
