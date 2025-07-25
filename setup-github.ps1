# Tunisia Governorate Cities WooCommerce Plugin - GitHub Setup Script
# This script helps set up the GitHub repository and push the code

Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "  Tunisia Governorate Cities WooCommerce Plugin" -ForegroundColor Cyan
Write-Host "  GitHub Repository Setup" -ForegroundColor Cyan
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host ""

# Check if Git is configured
Write-Host "Checking Git configuration..." -ForegroundColor Yellow
$gitName = git config --global user.name
$gitEmail = git config --global user.email

if (-not $gitName -or -not $gitEmail) {
    Write-Host "Git is not configured. Please configure your Git credentials:" -ForegroundColor Red
    Write-Host "git config --global user.name 'Your Name'" -ForegroundColor White
    Write-Host "git config --global user.email 'your.email@example.com'" -ForegroundColor White
    Write-Host ""
    Write-Host "After configuring Git, run this script again." -ForegroundColor Yellow
    exit 1
}

Write-Host "Git configured as: $gitName <$gitEmail>" -ForegroundColor Green
Write-Host ""

# Check if remote already exists
$remoteUrl = git remote get-url origin 2>$null
if ($remoteUrl) {
    Write-Host "GitHub remote already configured: $remoteUrl" -ForegroundColor Green
} else {
    Write-Host "No GitHub remote configured yet." -ForegroundColor Yellow
    Write-Host ""
    Write-Host "To set up GitHub repository:" -ForegroundColor Cyan
    Write-Host "1. Go to https://github.com and create a new repository" -ForegroundColor White
    Write-Host "2. Repository name: tunisia-governorate-cities" -ForegroundColor White
    Write-Host "3. Description: WordPress plugin for WooCommerce checkout with Tunisian governorates and cities" -ForegroundColor White
    Write-Host "4. Make it Public (recommended)" -ForegroundColor White
    Write-Host "5. Don't initialize with README (we have our own)" -ForegroundColor White
    Write-Host "6. Click 'Create repository'" -ForegroundColor White
    Write-Host ""
    
    $repoUrl = Read-Host "Enter your GitHub repository URL (e.g., https://github.com/yourusername/tunisia-governorate-cities.git)"
    
    if ($repoUrl) {
        git remote add origin $repoUrl
        Write-Host "GitHub remote added successfully!" -ForegroundColor Green
    } else {
        Write-Host "No URL provided. Please run this script again with a valid repository URL." -ForegroundColor Red
        exit 1
    }
}

Write-Host ""
Write-Host "Pushing code to GitHub..." -ForegroundColor Yellow

# Rename master to main (if needed)
$currentBranch = git branch --show-current
if ($currentBranch -eq "master") {
    git branch -M main
    Write-Host "Renamed branch from 'master' to 'main'" -ForegroundColor Green
}

# Push to GitHub
try {
    git push -u origin main
    Write-Host "Code pushed to GitHub successfully!" -ForegroundColor Green
} catch {
    Write-Host "Error pushing to GitHub. Please check your repository URL and permissions." -ForegroundColor Red
    exit 1
}

# Create and push release tag
Write-Host ""
Write-Host "Creating release tag..." -ForegroundColor Yellow
git tag -a v1.0.0 -m "Release version 1.0.0 - Complete Tunisia Governorate Cities WooCommerce Plugin"
git push origin v1.0.0
Write-Host "Release tag v1.0.0 created and pushed!" -ForegroundColor Green

Write-Host ""
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host "  SUCCESS! Your plugin is now on GitHub!" -ForegroundColor Green
Write-Host "===============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Go to your GitHub repository" -ForegroundColor White
Write-Host "2. Create a GitHub Release with the ZIP file" -ForegroundColor White
Write-Host "3. Add repository topics: wordpress, plugin, woocommerce, tunisia" -ForegroundColor White
Write-Host "4. Share your plugin with the community!" -ForegroundColor White
Write-Host ""
Write-Host "Plugin ZIP file ready for WordPress installation:" -ForegroundColor Green
Write-Host "tunisia-governorate-cities-v1.0.0.zip" -ForegroundColor White
Write-Host "" 