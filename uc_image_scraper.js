const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');
const https = require('https');

// ==========================================
// UC SSO Image Extractor Bot
// ==========================================

const TARGET_IMAGE_URL = process.argv[2] || "https://employee.uc.ac.id/index.php/file/get/sis/t_header_gp/0108012224061_logo_kantor.png";
const DOWNLOAD_DIR = path.join(__dirname, 'storage', 'app', 'public', 'extracted_images');

(async () => {
  // Ensure download directory exists
  if (!fs.existsSync(DOWNLOAD_DIR)) {
    fs.mkdirSync(DOWNLOAD_DIR, { recursive: true });
  }

  console.log('🤖 UC Image Extractor Bot Initializing...');
  
  // Launch browser in non-headless mode so YOU can log in to Microsoft/UC SSO if needed
  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext({
    acceptDownloads: true
  });
  const page = await context.newPage();

  console.log('🌐 Navigating to exactly your target URL...');
  const response = await page.goto(TARGET_IMAGE_URL);

  // If we got redirected to the login page (or Microsoft SSO)
  if (page.url().includes('login') || page.url().includes('microsoft') || page.url().includes('auth')) {
    console.log('🔐 Authentication Required! Please log in manually in the popup browser window.');
    console.log('⏳ Waiting up to 60 seconds for you to complete login...');
    
    // Wait for the URL to change back to our image or for network idle
    try {
      await page.waitForURL(url => url.toString() === TARGET_IMAGE_URL, { timeout: 60000 });
      console.log('✅ Login successful! Proceeding to download...');
    } catch (error) {
      console.log('❌ Login timed out or did not redirect back to the image URL.');
      await browser.close();
      process.exit(1);
    }
  }

  console.log('📥 Downloading image buffer...');
  // At this point, we are securely authenticated and viewing the image.
  // We can fetch the raw buffer directly from Playwright's page context (which holds the auth cookies)
  try {
      const imageResponse = await page.goto(TARGET_IMAGE_URL);
      const buffer = await imageResponse.body();
      
      const fileName = path.basename(TARGET_IMAGE_URL) || `extracted_${Date.now()}.png`;
      const savePath = path.join(DOWNLOAD_DIR, fileName);
      
      fs.writeFileSync(savePath, buffer);
      console.log(`🎉 SUCCESS! Image securely extracted and saved to: ${savePath}`);
  } catch (err) {
      console.error('Failed to extract image buffer:', err);
  }

  console.log('Closing bot...');
  await browser.close();
})();
